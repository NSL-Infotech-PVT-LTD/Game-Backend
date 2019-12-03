<div class="form-group{{ $errors->has('score') ? 'has-error' : ''}}">
    {!! Form::label('score', 'Score', ['class' => 'control-label']) !!}
    {!! Form::text('score', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('score', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
