<body>
<div class="container">

<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::to('settings/users') }}">Nerd Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('settings/users') }}">View All Nerds</a></li>
        <li><a href="{{ URL::to('settings/users/create') }}">Create a Nerd</a>
    </ul>
</nav>

<h1>Edit {{ $user->name }}</h1>

{{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Имя') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('surname', 'Фамилия') }}
        {{ Form::text('surname', Input::old('surname'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('father_name', 'Отчество') }}
        {{ Form::text('father_name', Input::old('father_name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('phone', 'Телефон') }}
        {{ Form::text('phone', Input::old('phone'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', Input::old('email'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('role', 'Группа пользователей') }}
        {{ Form::select('role', array('admin' => 'Администраторы', 'auditor' => 'Аудиторы', 'expert' => 'Эксперты', 'gazprom' => 'Газпром', 'test' => 'Тестеры'), Input::old('role'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('platform', 'Площадка') }}
        {{ Form::select('platform', array('1' => 'Домодедово', '2' => 'Лебедянь', '3' => 'Алкон'), Input::old('role'), array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Отредактировать пользователя!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}





</div>
</body>