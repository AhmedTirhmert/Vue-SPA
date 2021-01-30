<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-12 mb-3">
        <div class="chartCard d-flex flex-column">
          <h2 class="text-center">
            {{ ChartData.datasets.length }}- Products Progress
          </h2>
          <small class="text-center text-muted mb-3">
            ({{ ChartData.labels[0] }} /
            {{ ChartData.labels[ChartData.labels.length - 1] }})
          </small>
          <div v-if="!loaded" class="text-info text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong> Chart Loading ... </strong>
          </div>
          <line-chart v-else :ChartData="ChartData" />
          <div class="col">
            <button class="btn btn-primary w-100 mt-3" @click="RefreshChart">
              Refresh Chart
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="mb-3">
          <div class="chartCard">
            <h2 class="text-center">Chart 3</h2>
            <pie-chart />
          </div>
        </div>
        <div class="mb-3">
          <div class="chartCard">
            <h2 class="text-center">Chart 3</h2>
            <pie-chart />
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="mb-3">
          <h2 class="text-center">Chart 3</h2>
          <div v-if="!loaded" class="text-warning text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong> Chart Loading ... </strong>
          </div>
          <doughnut-chart v-else :ChartData="ChartData" />
        </div>
        <div class="mb-3">
          <h2 class="text-center">Chart 3</h2>
          <div v-if="!loaded" class="text-danger text-center my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong> Chart Loading ... </strong>
          </div>
          <doughnut-chart v-else :ChartData="ChartData" />
        </div>
      </div>

      <div class="col-md-12 mb-3">
        <div class="chartCard">
          <h2 class="text-center">Chart 3</h2>
          <bar-chart v-if="loaded" :ChartData="ChartData" />
        </div>
      </div>
    </div>
    <add-client-modal />
  </div>
</template>

<script>
import axios from "@/axios";
import BarChart from "../components/BarChart";
import LineChart from "../components/LineChart";
import PieChart from "../components/PieChart";
import DoughnutChart from "../components/DoughnutChart";
import AddClientModal from "../components/Clients/AddClient";

export default {
  components: {
    AddClientModal,
    BarChart,
    LineChart,
    PieChart,
    DoughnutChart,
  },
  data() {
    return {
      loaded: false,
      ChartData: {
        datasets: [],
      },
      colors: [
        "#007bff",
        "#6610f2",
        "#e83e8c",
        "#dc3545",
        "#ed300a",
        "#28a745",
        "#17a2b8",
        "#343a40",
      ],
    };
  },
  methods: {
    RefreshChart() {
      this.$bvModal.show("AddClient");
    },
  },
  mounted() {},
  async created() {
    await axios({
      method: "get",
      url: "testing",
      params: {
        productId: 1,
      },
    })
      .then((response) => {
        let Product = response.data.Res;
        // this.ChartData.labels = Product.ProductData.map((elm) => elm.date);
        let dates = [];
        var tmp;
        let col = 7;

        for (const elm of Product) {
          for (const val of elm.data) {
            if (!dates.includes(val.date)) {
              dates.push(val.date);
            }
          }
        }
        dates.sort();
        for (const elm of Product) {
          tmp = { data: [] };
          for (const val of dates) {
            tmp.data[dates.indexOf(val)] = 0;
          }
          for (const val of elm.data) {
            if (tmp.data[dates.indexOf(val.date)] != 0) {
              tmp.data[dates.indexOf(val.date)] += val.qty;
            } else {
              tmp.data[dates.indexOf(val.date)] = val.qty;
            }
          }
          tmp.borderColor = this.colors[col];
          tmp.backgroundColor = `${this.colors[col]}4d`;
          tmp.minBarWidth = 10;
          tmp.borderWidth = 1.2;
          tmp.label = elm.name;
          col -= 1;
          this.ChartData.datasets.push(tmp);
        }
        this.ChartData.labels = dates;
        setTimeout(() => {
          this.loaded = true;
        }, 1000);
      })
      .catch((err) => {
        err;
      });
  },
};
</script>

<style>
</style>
