<script>
import { Doughnut } from "vue-chartjs";
export default {
  extends: Doughnut,
  props: {
    ChartData: Object,
  },
  data: () => ({
    options: {
      legend: {
        display: false,
      },
      tooltips: {
        mode: "point",
      },
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [
          {
            display: false,
            stacked: false,
            ticks: {
              fontColor: "#000",
            },
          },
        ],
        yAxes: [
          {
            display: false,
            ticks: {
              beginAtZero: true,
              fontColor: "#000",
            },
          },
        ],
      },
    },
  }),

  created() {},
  mounted() {
    this.renderChart(this.ChartData, this.options);
  },
};
</script>

<style>
</style>