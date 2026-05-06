<!DOCTYPE html>
<html>
<head><title>Users Print Card</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .card { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; padding: 15px; width: 320px; display: inline-block; vertical-align: top; margin-right: 15px; }
    .header { background: #1a1a2e; color: white; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; margin: -15px -15px 15px -15px; }
    .badge { background: #f0f0f0; padding: 3px 8px; border-radius: 12px; font-size: 11px; display: inline-block; margin: 2px; }
    @media print { .no-print { display: none; } .card { break-inside: avoid; } }
</style>
</head>
<body>
<div class="no-print" style="text-align:center; margin-bottom:20px;">
    <button onclick="window.print()">Print</button>
    <button onclick="window.close()">Close</button>
</div>
@foreach($users as $user)
<div class="card">
    <div class="header"><strong>HIGHCUSTOM JEWELLERS</strong><br><small>{{ $user->user_code ?? 'EMP-'.$user->id }}</small></div>
    <div><strong>{{ $user->name }} {{ $user->lastname }}</strong></div>
    <div>{{ $user->email }} | {{ $user->mobile }}</div>
    @if($user->profile_image) <img src="{{ asset('storage/'.$user->profile_image) }}" style="width:60px; height:60px; border-radius:50%; margin:10px 0;"> @endif
    <div>Role: {{ $user->is_admin ? 'Admin' : 'User' }}</div>
    <div>Status: <span style="color: {{ $user->status == 'active' ? 'green' : 'red' }}">{{ ucfirst($user->status) }}</span></div>
    <div><strong>App Rights:</strong> @foreach(json_decode($user->app_rights ?? '[]', true) as $r) <span class="badge">{{ $r }}</span> @endforeach</div>
    <div><strong>Access Rights:</strong> @foreach(json_decode($user->access_rights ?? '[]', true) as $r) <span class="badge">{{ $r }}</span> @endforeach</div>
</div>
@endforeach
</body>
</html>
