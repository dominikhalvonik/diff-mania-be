@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="p-4">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded shadow flex flex-col items-center justify-center">
      <h2 class="text-xl font-semibold text-center">Total Registered Users</h2>
      <p class="text-5xl font-bold text-center">{{ $totalUsers }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow flex flex-col items-center justify-center">
      <h2 class="text-xl font-semibold text-center">Total Banned Users</h2>
      <p class="text-5xl font-bold text-center">{{ $totalBannedUsers }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow flex flex-col items-center justify-center">
      <h2 class="text-xl font-semibold text-center">Last 24 Hours Logins</h2>
      <p class="text-5xl font-bold text-center">{{ $activeUsers }}</p>
    </div>
    <div class="bg-white p-4 rounded shadow flex flex-col items-center justify-center">
      <h2 class="text-xl font-semibold text-center">Total Boosters Distributed</h2>
      <p class="text-5xl font-bold text-center">{{ $totalBoosters }}</p>
    </div>
  </div>

  <div class="flex justify-between mb-8">
    <div class="w-full">
      <h2 class="text-xl font-semibold mb-2">Daily New Registered Users (Last 7 Days)</h2>
      <canvas id="dailyRegistrationsChart" class="w-full h-64"></canvas>
    </div>
  </div>

  <h2 class="text-xl font-semibold mb-2">Recent Registrations</h2>
  <table class="table-auto w-full mb-8">
    <thead>
      <tr>
        <th class="px-4 py-2">Name</th>
        <th class="px-4 py-2">Email</th>
        <th class="px-4 py-2">Registered At</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($recentRegistrations as $user)
      <tr>
        <td class="border px-4 py-2">{{ $user->name }}</td>
        <td class="border px-4 py-2">{{ $user->email }}</td>
        <td class="border px-4 py-2">{{ $user->created_at }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var dailyRegistrationsCtx = document.getElementById('dailyRegistrationsChart').getContext('2d');
    var dailyRegistrationsChart = new Chart(dailyRegistrationsCtx, {
      type: 'bar',
      data: {
        labels: @json($dailyRegistrations->pluck('date')),
        datasets: [{
          label: 'New Registrations',
          data: @json($dailyRegistrations->pluck('count')),
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          x: {
            type: 'time',
            time: {
              unit: 'day'
            }
          },
          y: {
            beginAtZero: true
          }
        }
      }
    });
  });
</script>
@endsection