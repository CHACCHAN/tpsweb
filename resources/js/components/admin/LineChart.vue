<script lang="ts">
import { Chart, ChartData, registerables } from "chart.js";
import { defineComponent } from "vue";
import { LineChart } from "vue-chart-3";

Chart.register(...registerables);

export default defineComponent({
  components: {
    LineChart,
  },
  setup() {

    // LineChart 用のデータ
    const lineData: ChartData<"line"> = {
		labels: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
		datasets: [
			{
				label: "月間の閲覧量",
				data: [65, 59, 80, 81, 56, 55, 40],
				fill: true,
				borderColor: "rgb(75, 192, 192)",
				tension: 0.1,
			},
			// {
			// 	label: "2月間の閲覧量",
			// 	data: [65, 59, 80, 81, 56, 55, 40],
			// 	fill: true,
			// 	borderColor: "rgb(75, 192, 192)",
			// 	tension: 0.1,
			// },
		],
    };

    return { lineData };
  },
});
</script>

<template>
	<div class="wrapper">
		<LineChart class="w-100" :chartData="lineData" />
	</div>
</template>

<style scoped>
.wrapper {
  display: flex;
}
</style>