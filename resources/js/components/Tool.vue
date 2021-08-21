<template>
    <div>
        <div class="title mb-6 mt-6">وضعیت میکروسرویس ها و Supervisor</div>
        <card class="mb-6">
            <table class="table w-full">
                <thead>
                <tr>
                    <th scope="col">میکروسرویس</th>
                    <th scope="col">وضعیت سرویس</th>
                    <th scope="col">وضعیت Supervisor</th>
                    <th scope="col">عملیات Supervisor</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="microservice in microservices">
                    <td style="text-align: center">{{ capitalizeFirstLetter(microservice.name.replace('ml_', '')) }}</td>
                    <td style="text-align: center">
                        <div :class="['service-status', {'blink' : !microservice.status}]">
                            <div style="text-align: center; text-transform:capitalize; font-weight: bold"></div>
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div :class="['supervisor-status',
                         {'blink' : microservice['supervisor']['statename'] === 'FATAL' || microservice['supervisor']['statename'] === 'BACKOFF' || microservice['supervisor']['statename'] === 'NOT FOUND'},
                         {'starting' : microservice['supervisor']['statename'] === 'STARTING' || microservice['supervisor']['statename'] === 'STOPPED'},
                         {'runnig' : microservice['supervisor']['statename'] === 'RUNNING'}
                         ]">
                            <div v-if="microservice['supervisor'] == []" style="text-align: center;">
                                یافت نشد
                            </div>
                            <div v-else style="text-align: center;">
                                {{ microservice['supervisor']['statename'] }}
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center">
                        <button :disabled="disableButton" class="btn btn-success" @click="startSupervisor(microservice.name)">Start</button>
                        <button :disabled="disableButton" class="btn btn-success" @click="restartSupervisor(microservice.name)">Restart</button>
                        <button :disabled="disableButton" class="btn btn-danger" @click="stopSupervisor(microservice.name)">Stop</button>
                    </td>
                </tr>
                </tbody>
            </table>
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
            disableButton: false,
            microservices: {},
            models: {},
            interval: null,
            errorChartSeries: [{
                name: "Errors",
                data: []
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
        this.interval = setInterval(() => {
            this.getMicroservices();
        }, 15000)
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        startSupervisor(microservice) {
            this.disableButton = true;
            Nova.request().get(`/nova-vendor/milyoona/microservice-monitor/supervisor/start/${microservice}`)
                .then(response => {
                    this.disableButton = false;
                    if (response.data.status === 200) {
                        this.$toasted.show(response.data.message, {type: "success"});
                    } else {
                        this.$toasted.show(response.data.message, {type: "error"});
                    }
                })
                .catch(error => {
                    this.disableButton = false;
                    this.$toasted.show("خطای ناشناس", {type: "error"});
                })
        },
        restartSupervisor(microservice) {
            this.disableButton = true;
            Nova.request().get(`/nova-vendor/milyoona/microservice-monitor/supervisor/restart/${microservice}`)
                .then(response => {
                    this.disableButton = false;
                    if (response.data.status === 200) {
                        this.$toasted.show(response.data.message, {type: "success"});
                    } else {
                        this.$toasted.show(response.data.message, {type: "error"});
                    }
                })
                .catch(error => {
                    this.disableButton = false;
                    this.$toasted.show("خطای ناشناس", {type: "error"});
                })
        },
        stopSupervisor(microservice) {
            this.disableButton = true;
            Nova.request().get(`/nova-vendor/milyoona/microservice-monitor/supervisor/stop/${microservice}`)
                .then(response => {
                    this.disableButton = false;
                    if (response.data.status === 200) {
                        this.$toasted.show(response.data.message, {type: "success"});
                    } else {
                        this.$toasted.show(response.data.message, {type: "error"});
                    }
                })
                .catch(error => {
                    this.disableButton = false;
                    this.$toasted.show("خطای ناشناس", {type: "error"});
                })
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
    width: 20px;
    background-color: rgb(90, 230, 172);
    display: inline-block;
    border-radius: 1px;
    padding: 10px;
    margin: 5px;
    border-radius: 50%;
}

.supervisor-status {
    width: 100px;
    display: inline-block;
    border-radius: 1px;
    padding: 10px;
    margin: 5px;
    border-radius: 8px;
}

.runnig {
    background-color: rgb(90, 230, 172) !important;
}

.stop {
    background-color: #dc3545 !important;
}

.starting {
    background-color: #f3d618 !important;
}

.chart {
    padding: 20px;
}

.chart * {
    direction: ltr !important;
    font-family: Dana !important;
}

.btn {
    border-radius: 8px;
    text-align: center;
    font-weight: normal;
    padding: 10px;
    margin: 0 10px;
    outline: none !important;
}

.btn-success {
    background-color: rgb(90, 230, 172);
}

.btn-success:hover:enabled {
    background-color: green;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-danger:hover:enabled {
    background-color: #840000;
}
</style>
