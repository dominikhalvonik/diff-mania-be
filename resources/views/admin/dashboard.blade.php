@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="p-4">
  <h2 class="text-2xl font-bold mb-4">Total Registered Users: {{ $totalUsers }}</h2>
  <div class="flex justify-between">
    <div class="w-full">
      <h2 class="text-xl font-semibold mb-2">Daily New Registered Users (Last 7 Days)</h2>
      <canvas id="dailyRegistrationsChart" class="w-full h-64"></canvas>
    </div>
  </div>
</div>
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
        labels: {!! json_encode($dailyRegistrations->pluck('date')->toArray()) !!},
        datasets: [{
          label: 'New Users',
          data: {!! json_encode($dailyRegistrations->pluck('count')->toArray()) !!},
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
</script>
@endsection