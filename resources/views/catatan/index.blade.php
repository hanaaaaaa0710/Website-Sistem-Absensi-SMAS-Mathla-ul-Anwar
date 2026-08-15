@extends('layouts.master')
@section('content')
<h1>Catatan Absensi</h1>
<table>
<thead>
<tr>
<th>No</th><th>Siswa</th><th>Catatan</th><th>Dibuat Oleh</th><th>Tanggal</th>
</tr>
</thead>
<tbody>
@foreach($catatan as $c)
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $c->siswa->nama_siswa }}</td>
<td>{{ $c->catatan }}</td>
<td>{{ $c->creator->name }}</td>
<td>{{ $c->created_at->format('d-m-Y H:i') }}</td>
</tr>
@endforeach
</tbody>
</table>
{{ $catatan->links() }}
@endsection