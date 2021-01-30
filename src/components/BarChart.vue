<script>
import { Bar, mixins } from "vue-chartjs";
const reactiveChart = mixins;
export default {
  extends: Bar,
  props: {
    reactiveChart,
    ChartData: Object,
  },
  data: () => ({
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

            ticks: {
              fontColor: "#000",
            },
          },
        ],
        yAxes: [
          {
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