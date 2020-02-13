<link rel="stylesheet" type="text/css" href="{{ asset('dist/bootstrap-clockpicker.min.css') }}">
<div class="form-group{{ $errors->has('image') ? 'has-error' : ''}}">
    {!! Form::label('image', 'Image', ['class' => 'control-label']) !!}
    {!! Form::file('image', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('date') ? 'has-error' : ''}}">
    {!! Form::label('date', 'Start Date', ['class' => 'control-label']) !!}
    {!! Form::date('date', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('start_time') ? 'has-error' : ''}}">
    {!! Form::label('start_time', 'Start_time', ['class' => 'control-label']) !!}
    {!! Form::text('start_time', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group{{ $errors->has('fee') ? 'has-error' : ''}}">
    {!! Form::label('fee', 'Fee', ['class' => 'control-label']) !!}
    {!! Form::number('fee', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('fee', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('fee') ? 'has-error' : ''}}">
    {!! Form::label('sequential_fee', 'Sequential_fee', ['class' => 'control-label']) !!}
    {!! Form::number('sequential_fee', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('sequential_fee', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('prize_image') ? 'has-error' : ''}} hide-content">
    {!! Form::label('prize_image', 'Prize Image', ['class' => 'control-label']) !!}
    {!! Form::file('prize_image', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('prize_image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('prize_details') ? 'has-error' : ''}}">
    {!! Form::label('prize_details', 'Prize Details', ['class' => 'control-label']) !!}
    {!! Form::textarea('prize_details', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('prize_details', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('game_id') ? ' has-error' : ''}} hide-content">
    {!! Form::label('game_id', 'Games: ', ['class' => 'control-label']) !!}
    {!! Form::select('game_id', $game, isset($competition->game_id) ? $competition->game_id : [], ['class' =>
    'form-control',
    'multiple' => false]) !!}
</div>
{!! Form::label('hot_competitions', 'Hot Competition: ', ['class' => 'control-label']) !!}
<div class="checkbox">
    <label>{!! Form::radio('hot_competitions', '1') !!} Yes</label>
</div>
<div class="checkbox">
    <label>{!! Form::radio('hot_competitions', '0') !!} No</label>
</div>

<br>
<div class="form-group{{ $errors->has('competition_category_id') ? ' has-error' : ''}} ">
    {!! Form::label('competition_category_id', 'Competition Category:', ['class' => 'control-label']) !!}
    {!! Form::select('competition_category_id', $competition_category, isset($competition->competition_category_id) ? $competition->competition_category_id : [], ['class' =>
    'form-control',
    'multiple' => false]) !!}
</div>

<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
<script type="text/javascript" src="{{ asset('dist/bootstrap-clockpicker.min.js') }}"></script>
<script type="text/javascript">
$('#start_time').clockpicker({
    donetext: 'Set',
    twelvehour: true,
});
</script>