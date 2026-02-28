@props([
    'includePost' => false,
])

<script src="{{ asset('js/index.js') }}"></script>
@if($includePost)
    <script src="{{ asset('js/post.js') }}"></script>
@endif
