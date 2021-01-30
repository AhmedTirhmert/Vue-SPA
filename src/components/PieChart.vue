<script>
import { Pie } from "vue-chartjs";

export default {
  extends: Pie,
  data: () => ({
    chartdata: {
      labels: [
        "January",
        "February",
        "January",
        "February",
        "January",
        "February",
        "January",
        "January",
      ],
      datasets: [
        {
          label: "Ikhan",
          minBarLength: 0,
          backgroundColor: "#4dd364",
          data: [[50, 60], 10, 20, 30, 40, 50, 60, 100],
        },
      ],
    },
    options: {
      tooltips: {
        mode: "point",
      },
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [
          {
            stacked: false,
            gridLines: {
              offsetGridLines: true,
            },
            ticks: {
              fontColor: "#000",
            },
          },
        ],
        yAxes: [
          {
            stacked: false,
            ticks: {
              beginAtZero: true,
              fontColor: "#000",
            },
          },
        ],
      },
    },
  }),

  mounted() {
    this.renderChart(this.chartdata, this.options);
  },
};
</script>

<style>
</style>