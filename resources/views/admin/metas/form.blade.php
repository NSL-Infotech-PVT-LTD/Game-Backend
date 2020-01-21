<div style="display:none" class="form-group{{ $errors->has('meta_key') ? 'has-error' : ''}}">
    {!! Form::label('meta_key', 'Meta Key', ['class' => 'control-label']) !!}
    {!! Form::text('meta_key', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('meta_key', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('meta_content') ? 'has-error' : ''}}">
    {!! Form::label('meta_content', 'Meta Content', ['class' => 'control-label']) !!}
    {!! Form::textarea('meta_content', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('meta_content', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
