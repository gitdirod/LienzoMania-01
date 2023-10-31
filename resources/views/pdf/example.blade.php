@extends('pdf.layout')

@section('content')
<h1>{{$title}}</h1>
<div class="note">
    <h1 class=" text-xl text-green-500 font-extrabold">page 1</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit provident fuga voluptate soluta omnis
        pariatur
        ratione deleniti tempore voluptatum! Earum enim nam obcaecati dicta magni nemo, mollitia maxime nisi culpa?
    </p>
</div>
<div class="page-break"></div>

<div class="note">
    <h1 class=" text-red-500">page 2</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit provident fuga voluptate soluta omnis
        pariatur
        ratione deleniti tempore voluptatum! Earum enim nam obcaecati dicta magni nemo, mollitia maxime nisi culpa?
    </p>
</div>
@endsection