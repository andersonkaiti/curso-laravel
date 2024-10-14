@extends('layouts.main')

@section('title', 'HDC Events')

@section('content')

    <div id="event-create-container" class="col-md-6 offset-md-3">
        <h1>Crie o seu evento</h1>
        <form action="/events" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="image">Imagem do Evento:</label>
                <input type="file" id="image" name="image" class="form-control-file" required>
            </div>
            <div class="form-group">
                <label for="title">Evento:</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Nome do evento" required>
            </div>
            <div class="form-group">
                <label for="date">Data do evento:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="city">Cidade:</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Local do evento" required>
            </div>
            <div class="form-group">
                <label for="title">O evento é privado?:</label>
                <select name="private" id="private" class="form-control" required>
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" class="form-control" placeholder="O que vai acontecer no evento?" required></textarea>
            </div>
            <div class="form-group">
                <label for="description">Adicione itens de infraestrutura:</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="items[]" id="Cadeiras" value="Cadeiras">
                    <label for="Cadeiras">Cadeiras</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="items[]" id="Palco" value="Palco">
                    <label for="Palco">Palco</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="items[]" id="Cerveja grátis" value="Cerveja grátis">
                    <label for="Cerveja grátis">Cerveja grátis</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="items[]" id="Open Food" value="Open Food">
                    <label for="Open Food">Open Food</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="items[]" id="Brindes" value="Brindes">
                    <label for="Brindes">Brindes</label>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Criar Evento">
        </form>
    </div>

@endsection
