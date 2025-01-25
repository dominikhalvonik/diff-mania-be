@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('header', 'Dashboard')

@section('content')
  <div>
    <h2>Total Registered Users: {{ $totalUsers }}</h2>
  </div>
  <div style="display: flex; justify-content: space-between;">
    <div>
      <h2>Daily New Registered Users (Last 7 Days)</h2>
      <canvas id="dailyRegistrationsChart" width="400" height="200"></canvas>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var dailyRegistrationsCtx = document.getElementById('dailyRegistrationsChart').getContext('2d');
      var dailyRegistrationsChart = new Chart(dailyRegistrationsCtx, {
        type: 'line',
        data: {
          labels: {!! json_encode($dailyRegistrations->pluck('date')->toArray()) !!},
          datasets: [{
            label: 'New Users',
            data: {!! json_encode($dailyRegistrations->pluck('count')->toArray()) !!},
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
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