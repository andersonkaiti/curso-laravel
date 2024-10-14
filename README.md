# O que é Laravel?

É um `framework` construído na linguagem PHP que utiliza a `arquitetura MCV (Model, View e Controller)`, separando as responsabilidades da seguinte forma:

- `Model:` lida com os dados do banco.
- `Controller:` onde fica a maior parte da lógica da aplicação (CRUD), validação etc.
- `View:` onde os dados são exibidos (template engine: Blade).

Contém recursos muito interessantes que auxiliam o desenvolvimento de aplicações, como o Artisan, as Migrations, o Blade etc.

- `Artisan:` CLI que ajuda a criar alguns recursos sem digitar do zero.
- `Migrations:` ajuda a trabalhar com o banco de dados, criando e deletando registros.
- `Blade:` é o template engine, que ajuda a manusear a View da aplicação.

A estrutura de pastas é simples, tornando o projeto organizado.

# Instalar Composer

Para gerar um projeto, é necessário instalar o Composer e executar o seguinte comando:

```
composer create-project --prefer-dist laravel/laravel <name>
```

Após gerar o projeto, basta executar o seguinte comando para inicializar o servidor:

```
php artisan serve
```

# Rotas e Views

As páginas da aplicação são acessadas por meio de rotas. Cada rota define a URL que o usuário utilizará para acessar uma página específica.

As rotas chamam os `Controllers` ou `Views diretamente`. As Views são representações gráficas das páginas, onde os templates são definidos, ou seja, são nelas que ocorre a estruturação da página por meio do HTML. Dessa forma, os dados dinâmicos são renderizados por meio do PHP.

# Conhecendo o Blade

O Blade é o template engine do Laravel. Com ele, é possível deixar as Views mais dinâmicas. Além disso, ele permite extrair o PHP puro e separá-lo do HTML.

# Diretivas do Blade

As diretivas geralmente começam com `@`, permitindo adicionar `condicionais`, `estruturas de repetição` etc.

```php
@if(10 > 5)
    <p>A condição é true</p>
@endif
```

# Adicionando arquivos estáticos

Uma aplicação web normalmente contém arquivos de CSS, JavaScript e imagens. O Laravel proporciona uma maneira fácil de inserir esses arquivos no projeto adicionado todos na pasta /public.

```php
<img src="/img/event_placeholder.jpg" alt="{{ $event->title }}">
```

# Laravel com Blade

A funcionalidade de criar um `layout permite o reaproveitamento de código`. Com isso, é possível utilizar o mesmo header e footer em todas as páginas sem repetir código.

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        ...
        <title>@yield('title')</title>
    </head>
    <body>
        <header>
            ...
        </header>
        @yield('content')
        <footer>
            <p>HDC Events &copy; 2024</p>
        </footer>
    </body>
</html>
```

```php
@extends('layouts.main')

@section('title', 'HDC Events')

@section('content')

@endsection
```

# Parâmetros nas rotas

É possível mudar como uma View responde adicionando parâmetros a uma rota. Ao definir a rota, deve-se colocar o parâmetro desta maneira: `{id}`.

```php
Route::get('/products/{id}', function ($id = null) {
    return view('product', ['id' => $id]);
});
```

- Para adicionar `parâmetros adicionais`, basta adicionar um `?`.
- O Laravel também aceita os query params, como: `?nome=Matheus&idade=29`.

# Controllers

Geralmente condensam a maior parte da lógica e contêm uma série de `métodos chamados de actions`, que contêm o papel de enviar e esperar respostas do banco de dados, além de receber e enviar alguma resposta para as Views.

Os Controllers podem ser criados com o Artisan por meio do seguinte comando.

```
php artisan make:controller EventController
```

```php
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        return view('welcome');
    }
}
```

Além disso, é comum `enviar uma View` ou `redirecionar` para uma URL pelo Controller.

# Conexão com o banco de dados

A conexão do Laravel com o banco é configurada pelo `arquivo .env`, ou seja, `não é necessário implementar a conexão, nem instalar qualquer biblioteca`, mas sim apenas adicionar os dados de configuração no arquivo de variáveis.

O Laravel utiliza um ORM (Oject-Relational Mapping) chamado Eloquent, além das Migrations para a criação das tabelas.

# Migrations

As Migrations funcionam como um versionamento do banco de dados.

Com elas, é possível avançar e retroceder mudanças, adicionar e remover colunas, além de configurar o banco de dados em uma nova instalação com apenas um comando. Caso alguém da equipe baixe o projeto, para configurar o banco de dados basta executar o comando `php artisan migrate`.

## Comandos da Migrations

É possível verificar a situação das Migrations com o comando.

```
php artisan migrate:status
```

```
Migration name .........................Batch / Status  
0001_01_01_000000_create_users_table ..........Pending  
0001_01_01_000001_create_cache_table ..........Pending  
0001_01_01_000002_create_jobs_table ...........Pending
```

Criar uma Migration com o comando.

```
php artisan make:migration create_events
```

```php
return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('description');
            $table->string('city');
            $table->boolean('private');
        });
    }

    public function down(): void {
        Schema::dropIfExists('events');
    }
};
```

Adicionar uma coluna com o seguinte comando.

```
php artisan make:migration add_image_to_events_table
```

```php
return new class extends Migration {
    public function up(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->string('image');
        });
    }
    
    public function down(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
```

Caso alguma coluna seja acrescentada, para atualizar basta utilizar o seguinte comando.

```
php artisan migrate
```

Para deletar todos os registros e executar as Migrations novamente, basta utilizar o seguinte comando.

```
php artisan migrate:fresh
```

Quando é necessário adicionar um novo campo a uma tabela, deve-se criar uma nova migration. Porém, é necessário tomar cuidado para não executar o comando fresh e apagar os dados já existentes.

- `Rollback:` para reverter a última Migration executada.
- `Reset:` para reverter todas as Migrations de uma vez.
- `Refresh:` para reverter todas as migrations e executá-las novamente.

# Eloquent

O `Eloquent` é a `ORM (Object-Relational Mapping) do Laravel`, que fornece uma `abstração para as consultas`, permitindo trabalhar com o banco de dados utilizando classes em vez de comandos SQL.

Para cada tabela, existe um Model que é responsável pela interação entre as requisições do banco. A convenção para o Model é o nome da entidade em singular, enquanto a tabela é a entidade no plural. Ou seja: Event para o Model e events para a tabela.

Para criar um Model, basta utiliza o seguinte comando.

```
php artisan make:model Event
```

# Adicionando registro ao banco de dados

No Laravel, é comum ter uma action específica para o post chamada de `store`, que é onde se cria o objeto, compõe-o com base nos dados enviados pelo POST e, com o objeto formado, utiliza-se o `método save` para `persistir os dados`.

```php
Route::post('events', [EventController::class, 'store']);
```

Os dados do POST são enviados para o `Controller` e a `action store`, que os recebe pela `variável $request`, cria um objeto Event, adiciona as propriedades e salva os dados no banco de dados.

```php
public function store(Request $request) {
    $event = new Event;

    $event->title = $request->title;
    $event->date = $request->date;
    $event->city = $request->city;
    $event->private = $request->private;
    $event->description = $request->description;
    $event->items = $request->items;

    $event->save();

    return redirect("/");
}
```

Para simplificar, é possível utilizar o `método only da variável request`, que seleciona apenas os dados requeridos, e `utilizar o retorno` dele como `argumento do método fill`, que preencherá os atributos da instância da classe Event.

```php
$event->fill($request->only(['title', 'date', 'city', 'private', 'description', 'items']));
```

Antes disso, é necessário adicionar o seguinte atributo na classe Event.

```php
protected $fillabel = ['title', 'date', 'city', 'private', 'description', 'items'];
```

# Flash Messages

É possível adicionar mensagens ao usuário por sessions. Essas mensagens, utilizadas para `apresentar um feedback ao usuário`, são conhecidas como `Flash Messages` e são adicionadas com o `método with nos Controllers`.

No Blade, é possível verificar a presença da mensagem pela diretiva @session.

```php
return redirect("/")->with('msg', 'Evento criado com sucesso!');
```

# Salvando imagem no Laravel

Para realizar o upload de imagens, é necessário mudar o `enctype do form` para `multipart/form-data` e também criar um input de envio deles.

```html
<form action="/events" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="image">Imagem do Evento:</label>
        <input type="file" id="image" name="image" class="form-control-file" required>
    </div>
</form>
```

```php
if($request->hasFile('image') && $request->file('image')->isValid()) {
    $image = $request->image;

    // Cria o nome da imagem que será salvo no banco de dados e adiciona a extensão do arquivo a ele
    $imageName = md5($image->getClientOriginalName() . strtotime('now')) . "." . $image->extension();

    // Move a imagem para a pasta img/events do seu projeto
    $image->move(public_path('img/events'), $imageName);

    // Salva o nome da imagem no banco de dados
    $event->image = $imageName;
}
```

# Resgatando registros

Para `resgatar todos os registros` de uma tabela, basta utilizar o `método estático all`.

```php
$events = Event::all();
```

Para `resgatar um registro específico`, é possível utilizar o `método estático findOrFail`, passando como `argumento o id`.

```php
$event = Event::findOrFail($id);
```

Para criar uma busca no Laravel, basta utilizar o `método estático where`, que realiza um `filtro nos dados` que serão enviados para a View.

```php
$events = Event::where('title', 'like', '%'.$search.'%')->get();
```

# Salvando JSON

É possível salvar um conjunto de dados no banco para itens de múltipla escolha. Para isso, basta criar um campo determinado do `tipo json por meio de migrations`.

```php
$table->json('items');
```

No front-end, basta utilizar inputs do tipo checkbox, selecionar o que for necessário e, após o envio para o Controller, os dados são persistidos no banco assim como os outros dados foram.

Além de adicionar o seguinte atributo ao Model Event, permitindo que o atributo items seja convertido em array ao ser persistido no banco de dados.

```php
protected $casts = [
    'items' => 'array'
];
```

# Salvando datas

Para salvar datas, é necessário criar um input do `tipo date na View`, adicionar um campo de datetime por meio das migrations e processar o envio dos dados por meio do Controller pelo request de POST. O próprio Laravel já realiza todo o tratamento do dado de date.

```html
<form action="/events" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="date">Data do evento:</label>
        <input type="date" class="form-control" id="date" name="date" required>
    </div>
</form>
```

```php
$event->date = $request->date;
```

Além de adicionar, junto ao items, o formato que a data terá ao ser persistida no banco de dados no atributo casts.

```php
protected $casts = [
    'items' => 'array',
    'date' => 'datetime'
];
```

E o seguinte atributo, que facilita realizar operações com datas.

```php
protected $dates = ['date'];
```

Para exibir datas na View, basta utilizar a seguinte função. Como a data armazenada no `$event->date` está armazenada no mesmo `formato do banco de dados (data/hora: 2024-10-13 19:12:59)`, a `função strtotime` converte a string em um `timestamp` Unix e a `função date` converte o timestamp para uma `string de data de acordo com o formato especificado` (neste caso, `d/m/Y`).

```php
date('d/m/Y', strtotime($event->date))
```

# Autenticação no Laravel

É possível utilizar o Jetstream para implementar autenticação de modo rápido no sistema. Para isso, é necessário instalar os pacotes por meio do Composer.

Primeiro, instale o pacote Laravel Jetstream, que oferece funcionalidade de autenticação, como registro, login etc.

```
composer require laravel/jetstream
```

Após isso, basta instalar o Livewire, que são os componentes de autenticação para o Blade.

```
php artisan jetstream:install livewire
```

O `Jetstream` gera algumas migrations, que geram tabelas necessárias para `adicionar funcionalidades de autenticação`. Por isso, é necessário utilizar o seguinte comando para realizar as migrações.

```
php artisan migrate
```

Instala todas as dependência do projeto de front-end.

```
npm install
npm run dev
```

# Relations (one to many)

Relações são essenciais para sistemas de banco de dados.

É possível criar uma relação de um para muitos entre o usuário e eventos, isso tornará um usuário dono de um ou vários eventos. Para isso, é necessário alterar as migrations, adicionando uma chave estrangeira no Model Event.

```php
$table->foreignId('user_id')->constrained();
```

Além de alterar o Model Event e User, permitindo que o Event pertença a múltiplos User e o Event pertença ao User, respectivamente.

```php
// User.php
public function events() {
    return $this->hasMany('App\Models\Event');
}
```

```php
// Event.php
public function user() {
    return $this->belongsTo('App\Models\User');
}
```

# Deletando eventos

Para `deletar um registro`, basta utilizar o `método estático findOrFail` e, em seguida, deletar utilizando o `método delete`. Como o usuário está associado ao evento, é realizada uma desassociação.

```php
$event = Event::findOrFail($id);
$event->users()->detach();

Event::findOrFail($id)->delete();
```

# Editando eventos

Para editar um registro, basta coletar todos os dados da variável request (oriundas do formulário de edição), utilizar o `método estático findOrFail` do Model Event e, caso haja um registro, atualizá-lo com o `método update`.

```php
$data = $request->all();

Event::findOrFail($request->id)->update($data);
```

Além de ser necessário alterar o Model Event, `adicionando o atributo guarded` e atribuindo a ele um `array vazio`, para que o evento seja atualizado `sem nenhuma restrição`.

```php
protected $guarded = [];
```

# Relations many to many

Assim como um usuário pode conter vários eventos, um evento pode conter vários participantes. Para isso, é necessário criar uma nova tabela que representa essa relação.

```php
Schema::create('event_user', function (Blueprint $table) {
    $table->foreignId('event_id')->constrained();
    $table->foreignId('user_id')->constrained();
    $table->timestamps();
});
```

Além de, novamente, alterar o Model Event e User, permitindo que o Event pertença a múltiplos User e o Event pertença a múltiplos User, respectivamente.

```php
// Event.php
public function users() {
    return $this->belongsToMany('App\Models\User');
}
```

```php
// User.php
public function eventsAsParticipant() {
    return $this->belongsToMany(Event::class, 'event_user', 'user_id', 'event_id');
}
```