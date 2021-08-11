<template>
    <div>
        <div class="title mb-6 mt-6">وضعیت میکروسرویس ها</div>
        <card class="mb-6">
            <div style="padding: 20px">
                <div v-for="microservice in microservices" :class="['service-status', {'blink' : !microservice.status}]">
                    <div style="text-align: center; text-transform:capitalize; font-weight: bold">{{ microservice.name.replace('ml_', '') }}</div>
                </div>
            </div>
        </card>


<!--        <div class="title mb-6 mt-6">خطاهای میکروسرویس ها</div>-->
        <card class="mb-6">
            <div class="chart" id="chart">
                <apexchart id="charttt" type="line" height="350" :options="errorChartOptions" :series="errorChartSeries"></apexchart>
            </div>
        </card>


<!--        <div v-for="model in models">-->
<!--            <div class="title mb-6 mt-6">دیتابیس {{ model }}</div>-->
<!--            <card class="mb-6">-->
<!--                <div :id="'chart' + model" class="chart"></div>-->
<!--            </card>-->
<!--        </div>-->


<!--        <div v-for="model in models">-->
<!--            <div class="title mb-6 mt-6">دیتابیس {{ model }}</div>-->
<!--            <card class="mb-6">-->
<!--                <table class="table w-full">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th scope="col">میکروسرویس</th>-->
<!--                        <th scope="col">تعداد کل رکوردها</th>-->
<!--                        <th scope="col">تعداد رکوردهای حدف شده</th>-->
<!--                        <th scope="col">تعداد بروزرسانی ها</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr v-for="microservice in microservices"-->
<!--                        v-if="microservice.all_models && typeof microservice.all_models[model.toLowerCase()] !== 'undefined'">-->
<!--                        <td style="text-align: center">{{ microservice.name }}</td>-->
<!--                        <td style="text-align: center">{{ microservice.all_models[model.toLowerCase()] }}</td>-->
<!--                        <td style="text-align: center">{{ microservice.deleted_models[model.toLowerCase()] }}</td>-->
<!--                        <td style="text-align: center; direction: ltr !important;">-->
<!--                            {{ microservice.updated_models[model.toLowerCase()] }}-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </card>-->
<!--        </div>-->


    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            microservices: {},
            models: {},
            errorChart: null,
            charts: [],
            errorChartSeries: [{
                name: "تعداد خطا",
                data: [10, 41, 35, 51, 49, 62, 69, 91, 148, 82, 10, 41, 35, 51, 49, 62, 69, 91, 148, 82]
            }],
            errorChartOptions: {
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: 'خطاهای میکروسرویس ها',
                    align: 'right'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    labels: {
                        show: true,
                        rotate: -45,
                        rotateAlways: true,
                        minHeight: 100,
                        maxHeight: 180,
                    },
                    tickPlacement: 'on',
                    categories: [],
                }
            },
        }
    },
    mounted() {
        this.getMicroservices();
        window.setInterval(() => {
            this.getMicroservices();
        }, 3000)
    },
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        getChartsData() {
            // for (const [key, value] of Object.entries(this.models)) {
            //
            //     let chart = am4core.create('chart' + value, am4charts.XYChart)
            //     this.charts[value] = chart
            //
            //     let data = [];
            //     for (var i = 0; i < this.microservices.length; i++) {
            //         if (this.microservices[i]['all_models'] && typeof this.microservices[i]['all_models'][value.toLowerCase()] !== 'undefined') {
            //             data.push({ category: this.microservices[i]['name'].replace('ml_', ''), value: this.microservices[i]['all_models'][value.toLowerCase()] });
            //         }
            //     }
            //     chart.data = data;
            //
            //     chart.padding(40, 40, 40, 40);
            //
            //     var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            //     categoryAxis.renderer.grid.template.location = 0;
            //     categoryAxis.dataFields.category = "category";
            //     categoryAxis.renderer.labels.template.rotation = -90;
            //     categoryAxis.renderer.labels.template.paddingTop = -12;
            //     categoryAxis.renderer.minGridDistance = 20;
            //
            //     var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            //
            //     var series = chart.series.push(new am4charts.LineSeries());
            //     series.dataFields.categoryX = "category";
            //     series.dataFields.valueY = "value";
            //     series.tooltipText = "{valueY.value}";
            //     series.tensionX = 0.8;
            //     series.strokeWidth = 3;
            //
            //     var bullet = series.bullets.create(am4charts.CircleBullet);
            //     bullet.isDynamic = true;
            //     bullet.circle.fill = am4core.color("#fff");
            //     bullet.strokeWidth = 3;
            //
            //     chart.cursor = new am4charts.XYCursor();
            // }
        },
        getErrorsChart() {
            var microservices = [];
            var consumerErrorLogs = [];
            for (var i = 0; i < this.microservices.length; i++) {
                if (!this.errorChartOptions.xaxis.categories.length) {
                    microservices.push(this.capitalizeFirstLetter(this.microservices[i]['name'].replace('ml_', '')));
                }
                consumerErrorLogs.push(this.microservices[i]['consumer_error_logs']);
            }

            if (!this.errorChartOptions.xaxis.categories.length) {
                this.errorChartOptions = {
                    xaxis: {
                        categories: microservices,
                    }
                }
            }

            this.errorChartSeries = [{
                data: consumerErrorLogs
            }]
        },
        getMicroservices() {
            Nova.request().get('/nova-vendor/milyoona/microservice-monitor/microservices/')
                .then(response => {
                    this.microservices = response.data.microservices;
                    this.models = response.data.models;

                    this.getErrorsChart();
                });
        }
    },
}
</script>

<style>
.title {
    font-size: 1rem;
}

.blink {
    animation: blink-animation 1s steps(5, start) infinite;
    -webkit-animation: blink-animation 1s steps(5, start) infinite;
    background-color: #dc3545 !important;
    color: white;
}

@keyframes blink-animation {
    to {
        visibility: hidden;
    }
}

@-webkit-keyframes blink-animation {
    to {
        visibility: hidden;
    }
}

.service-status {
    width: 110px;
    background-color: rgb(90, 230, 172);
    display: inline-block;
    border-radius: 1px;
    padding: 10px;
    margin: 5px;
}

.chart {
    padding: 20px;
}

.chart * {
    direction: ltr !important;
    font-family: Dana !important;
}
</style>
