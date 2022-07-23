Mostrar la lista de empleados

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Foto</th>
			<th>Nombre</th>
			<th>Apellido Paterno</th>
			<th>Apellido Materno</th>
			<th>Correo</th>
			<th>Acciones</th>
		</tr>
	</thead>

	<tbody>
		@foreach($empleados as $empleado)
		<tr>
			<td>{{$empleado->id}}</td>
			<td>
				<img src="{{asset('storage').'/'.$empleado->Foto}}" width="300" height="300"  alt="">	
			</td>
			<td>{{$empleado->Nombre}}</td>
			<td>{{$empleado->ApellidoPaterno}}</td>
			<td>{{$empleado->ApellidoMaterno}}</td>
			<td>{{$empleado->Correo}}</td>
			<td>
				<a href="{{url('/empleado/'.$empleado->id.'/edit')}}">
					Editar
				</a>		
				|
				<form action="{{url('/empleado/'.$empleado->id)}}" method="post">
					@csrf
					{{method_field('DELETE')}}
					<input type="submit" onclick="return confirm('Are you sure?')" value="Borrar">
				</form>
			
			</td>
		</tr>
		@endforeach
	</tbody>

</table>