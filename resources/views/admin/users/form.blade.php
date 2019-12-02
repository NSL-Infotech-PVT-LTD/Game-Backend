<div class="form-group{{ $errors->has('first_name') ? ' has-error' : ''}}">
    {!! Form::label('first_name', 'First Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('last_name') ? ' has-error' : ''}}">
    {!! Form::label('last_name', 'Last Name: ', ['class' => 'control-label']) !!}
    {!! Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', 'Password: ', ['class' => 'control-label']) !!}
    @php
        $passwordOptions = ['class' => 'form-control'];
        if ($formMode === 'create') {
            $passwordOptions = array_merge($passwordOptions, ['required' => 'required']);
        }
    @endphp
    {!! Form::password('password', $passwordOptions) !!}
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
    {!! Form::label('role', 'Role: ', ['class' => 'control-label']) !!}
    {!! Form::select('roles[]', $roles, isset($user_roles) ? $user_roles : [], ['class' => 'form-control', 'multiple' => true]) !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
