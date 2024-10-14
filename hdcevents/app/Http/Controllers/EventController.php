<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index() {
        $search = request('search');

        if($search) {
            $events = Event::where('title', 'like', '%'.$search.'%')->get();
        } else {
            $events = Event::all();
        }

        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    public function create() {
        return view('events.create');
    }

    private function saveImageOnPublicDirectory(Request $request) {
        $image = $request->image;

        // Cria o nome da imagem que será salvo no banco de dados e adiciona a extensão do arquivo a ele
        $imageName = md5($image->getClientOriginalName() . strtotime('now')) . "." . $image->extension();

        // Move a imagem para a pasta img/events do seu projeto
        $image->move(public_path('img/events'), $imageName);

        return $imageName;
    }

    public function store(Request $request) {
        $event = new Event;

        $event->fill($request->only(['title', 'date', 'city', 'private', 'description', 'items']));

        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageName = $this->saveImageOnPublicDirectory($request, $event);

            // Salva o nome da imagem no banco de dados
            $event->image = $imageName;
        }

        $user = Auth::user();
        $event->user_id = $user->id;

        $event->save();

        return redirect("/")->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id) {
        $event = Event::findOrFail($id);

        $user = Auth::user();

        $hasUserJoined = false;

        if($user) {
            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvent) {
                if($userEvent['id'] == $id) {
                    $hasUserJoined = true;
                }
            }
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);
    }

    public function dashboard() {
        $user = Auth::user();

        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsAsParticipant' => $eventsAsParticipant]);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $event->users()->detach();

        $path = public_path('img/events/' . $event->image);

        if(file_exists($path)) {
            unlink($path);
        }

        Event::findOrFail($id)->delete();


        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($edit) {
        $user = Auth::user();

        $event = Event::findOrFail($edit);

        if($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request) {
        $data = $request->all();


        // Verifica se algum arquivo de imagem foi enviado no request
        if($request->hasFile('image') && $request->file('image')->isValid()) {
            // Salva a nova imagem na pasta public
            $imageName = $this->saveImageOnPublicDirectory($request);

            // Salva o novo nome da imagem
            $data['image'] = $imageName;

            // Consulta o nome da antiga imagem no banco de dados
            $eventImage = Event::findOrFail($request->id)->image;

            // Com base no nome antigo, formata o caminho para a imagem antiga
            $oldImagePath = public_path('img/events/' . $eventImage);

            // Remove a antiga imagem da pasta public
            if(file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($id) {
        $user = Auth::user();

        // Método que foi criado no model User
        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title);
    }

    public function leaveEvent($id) {
        $user = Auth::user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);
    }
}
