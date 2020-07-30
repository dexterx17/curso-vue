@extends('principal')

@section('contenido')
<template v-if="menu==0">
    <h1>Contenido del menu 1</h1>
</template>
<template v-if="menu==1">
    <categoria-component></categoria-component>
</template>
<template v-if="menu==2">
    <articulo-component></articulo-component>
</template>
<template v-if="menu==3">
    <h1>Contenido del menu 3</h1>
</template>
<template v-if="menu==4">
    <h1>Contenido del menu 4</h1>
</template>
<template v-if="menu==5">
    <h1>Contenido del menu 5</h1>
</template>
@endsection