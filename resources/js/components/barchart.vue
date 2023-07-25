<template>
    <div>
      <canvas ref="chart"></canvas>
    </div>
  </template>

  <script>
  import { Bar } from 'vue-chartjs'

  export default {
    extends: Bar,
    props: ['data', 'options'],
    mounted () {
    setTimeout(() => {
      this.renderChart(this.chartData, this.chartOptions)
    }, 0)
    }
  }
  </script>

