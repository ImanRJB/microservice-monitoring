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


        <card class="mb-6">
            <div class="chart" id="chart">
                <apexchart type="line" height="350" :options="errorChartOptions" :series="errorChartSeries"></apexchart>
            </div>
        </card>



        <div class="title mb-6 mt-6">مغایرت کل رکوردهای دیتابیس</div>
        <card class="mb-6">
            <table class="table w-full">
                <thead>
                <tr>
                    <th scope="col">میکروسرویس</th>
                    <th scope="col">جدول دیتابیس</th>
                    <th scope="col">مغایرت</th>
                </tr>
                </thead>
                <tbody v-for="microservice in microservices">
                <tr v-for="(error, model) in microservice['model_errors']['all_models']" v-if="error !== 0">
                    <td style="text-align: center">{{ capitalizeFirstLetter(microservice.name.replace('ml_', '')) }}</td>
                    <td style="text-align: center">{{ capitalizeFirstLetter(model) }}</td>
                    <td v-if="typeof error === 'string'" style="text-align: center">{{ error }}</td>
                    <td v-else-if="error < 0" style="text-align: center; color: blue; font-weight: bold">{{ Math.abs(error) }} رکورد بیشتر از دیتابیس اصلی</td>
                    <td v-else-if="error > 0" style="text-align: center; color: red; font-weight: bold">{{ error }} رکورد کمتر از دیتابیس اصلی</td>
                </tr>
                </tbody>
            </table>
        </card>


        <div class="title mb-6 mt-6">مغایرت رکوردهای بروزرسانی شده</div>
        <card class="mb-6">
            <table class="table w-full">
                <thead>
                <tr>
                    <th scope="col">میکروسرویس</th>
                    <th scope="col">جدول دیتابیس</th>
                    <th scope="col">مغایرت</th>
                </tr>
                </thead>
                <tbody v-for="microservice in microservices">
                <tr v-for="(error, model) in microservice['model_errors']['updated_models']" v-if="error !== 0">
                    <td style="text-align: center">{{ capitalizeFirstLetter(microservice.name.replace('ml_', '')) }}</td>
                    <td style="text-align: center">{{ capitalizeFirstLetter(model) }}</td>
                    <td v-if="typeof error === 'string'" style="text-align: center">{{ error }}</td>
                    <td v-else-if="error < 0" style="text-align: center; color: blue; font-weight: bold">{{ Math.abs(error) }} رکورد بیشتر از دیتابیس اصلی</td>
                    <td v-else-if="error > 0" style="text-align: center; color: red; font-weight: bold">{{ error }} رکورد کمتر از دیتابیس اصلی</td>
                </tr>
                </tbody>
            </table>
        </card>


        <div class="title mb-6 mt-6">مغایرت رکوردهای حذف شده</div>
        <card class="mb-6">
            <table class="table w-full">
                <thead>
                <tr>
                    <th scope="col">میکروسرویس</th>
                    <th scope="col">جدول دیتابیس</th>
                    <th scope="col">مغایرت</th>
                </tr>
                </thead>
                <tbody v-for="microservice in microservices">
                <tr v-for="(error, model) in microservice['model_errors']['deleted_models']" v-if="error !== 0">
                    <td style="text-align: center">{{ capitalizeFirstLetter(microservice.name.replace('ml_', '')) }}</td>
                    <td style="text-align: center">{{ capitalizeFirstLetter(model) }}</td>
                    <td v-if="typeof error === 'string'" style="text-align: center">{{ error }}</td>
                    <td v-else-if="error < 0" style="text-align: center; color: blue; font-weight: bold">{{ Math.abs(error) }} رکورد بیشتر از دیتابیس اصلی</td>
                    <td v-else-if="error > 0" style="text-align: center; color: red; font-weight: bold">{{ error }} رکورد کمتر از دیتابیس اصلی</td>
                </tr>
                </tbody>
            </table>
        </card>

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
            errorChartSeries: [{
                name: "Errors",
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
        }, 5000)
    },
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
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
                    // this.$toasted.show("Yay, it worked!", {type: "success"});
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
