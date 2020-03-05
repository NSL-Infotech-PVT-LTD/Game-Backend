<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Image', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<!--<div class="form-group">
    <label class="control-label">Type</label>
    <select class="form-control" name="type">
        <option>Choose Any</option>
        <option value="home_page">Home Page</option>
        <option value="competition">Competition</option>
    </select>
</div>-->
<div class="form-group{{ $errors->has('type') ? ' has-error' : ''}}">
    {!! Form::label('type', 'Type: ', ['class' => 'control-label']) !!}
    {!! Form::select('type', ['home_page'=>'home_page','competition'=>'competition'], isset($banner->type) ? $banner->type : '', ['class' => 'form-control', 'multiple' => false]) !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
