<script lang="ts">
import { Chart, ChartData, registerables } from "chart.js";
import { defineComponent, onMounted, ref } from "vue";
import { LineChart } from "vue-chart-3";

Chart.register(...registerables);

export default defineComponent({
	components: {
		LineChart,
	},
	setup() {
		const date = new Date()
		const year = date.getFullYear()
		const month = date.getMonth() + 1
		const day = date.getDate()
		const lineData = ref({
			labels: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
			datasets: [
				{
					label: "月間の閲覧量",
					data: [],
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
		})

		onMounted(() => {
			getData()
		})

		const getData = () => {
			fetch('/tps-site/get/view/count')
			.then((response) => response.json())
			.then(res => {
				lineData.value.datasets[0].data = [
					res.responseData.january,
					res.responseData.february,
					res.responseData.march,
					res.responseData.april,
					res.responseData.may,
					res.responseData.june,
					res.responseData.july,
					res.responseData.august,
					res.responseData.september,
					res.responseData.october,
					res.responseData.november,
					res.responseData.december,
				]
			})
			.catch(error => {
				console.log(error)
			})
		}

		return { 
			lineData,
			year,
			month,
			day,
		}
	},
});
</script>

<template>
	<div class="fw-bold" v-text="year + '年' + month + '月' + day + '日 時点の情報'"></div>
	<div class="wrapper">
		<LineChart class="w-100" :chartData="lineData" />
	</div>
</template>

<style scoped>
.wrapper {
  display: flex;
}
</style>