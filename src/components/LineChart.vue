<script>
import { Line } from "vue-chartjs";
// import moment from "moment";
export default {
  extends: Line,
  props: {
    ChartData: Object,
  },
  data: () => ({
    options: {
      tooltips: {
        mode: "point",
      },
      responsive: true,
      maintainAspectRatio: false,
      backgroundColor: "#fff",

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