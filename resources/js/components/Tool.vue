<template>
    <div>
        <heading class="mb-6 mt-6">لیست میکروسرویس ها</heading>
        <card class="mb-6">
            <table class="table w-full">
                <thead>
                <tr>
                    <th scope="col">میکروسرویس</th>
                    <th scope="col">وضعیت</th>
                    <th scope="col">تعداد خطاها</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="microservice in microservices">
                    <td style="text-align: center">{{ microservice.name }}</td>
                    <td style="text-align: center">
                        <span style="width: 1.5rem; height: 1.5rem" v-if="microservice.status"
                              class="inline-block rounded-full w-2 h-2 mr-1 bg-success"></span>
                        <span style="width: 1.5rem; height: 1.5rem" v-else
                              class="inline-block rounded-full w-2 h-2 mr-1 bg-danger blink"></span>
                    </td>
                    <td style="text-align: center">{{ microservice.consumer_logs }}</td>
                </tr>
                </tbody>
            </table>
        </card>


        <div v-for="model in models">
            <heading class="mb-6 mt-6">دیتابیس {{ model }}</heading>
            <card class="mb-6">
                <table class="table w-full">
                    <thead>
                    <tr>
                        <th scope="col">میکروسرویس</th>
                        <th scope="col">تعداد کل رکوردها</th>
                        <th scope="col">تعداد رکوردهای حدف شده</th>
                        <th scope="col">آخرین آپدیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="microservice in microservices"
                        v-if="microservice.all_models && typeof microservice.all_models[model.toLowerCase()] !== 'undefined'">
                        <td style="text-align: center">{{ microservice.name }}</td>
                        <td style="text-align: center">{{ microservice.all_models[model.toLowerCase()] }}</td>
                        <td style="text-align: center">{{ microservice.deleted_models[model.toLowerCase()] }}</td>
                        <td style="text-align: center; direction: ltr !important;">
                            {{ microservice.updated_models[model.toLowerCase()] }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </card>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            microservices: {},
            models: {}
        }
    },
    mounted() {
        this.getMicroservices();
        window.setInterval(() => {
            this.getMicroservices();
        }, 3000)
    },
    methods: {
        // getStatus() {
        //     this.microservices.forEach(microservice => {
        //         Nova.request().get('/nova-vendor/milyoona/microservice-monitor/microservices/ml_auth/status')
        //             .then(response => {
        //                 microservice['status'] = response.status
        //                 if (response.status === 200) {
        //                     microservice['consumer_logs'] = response.data.consumer_logs
        //                     microservice['models'] = response.data.models
        //                     microservice['models_updated'] = response.data.models_updated
        //                     microservice['models_deleted'] = response.data.models_deleted
        //                 } else {
        //                     microservice['consumer_logs'] = '--'
        //                     microservice['models'] = '--'
        //                     microservice['models_updated'] = '--'
        //                     microservice['models_deleted'] = '--'
        //                 }
        //             })
        //             .catch(error => {
        //                 microservice['status'] = 500
        //                 microservice['consumer_logs'] = '--'
        //                 microservice['models'] = '--'
        //                 microservice['models_updated'] = '--'
        //                 microservice['models_deleted'] = '--'
        //             })
        //
        //     })
        // },
        getMicroservices() {
            Nova.request().get('/nova-vendor/milyoona/microservice-monitor/microservices/')
                .then(response => {
                    this.microservices = response.data.microservices;
                    this.models = response.data.models;
                });
        }
    },
}
</script>

<style>
.history-table {
    text-align: left;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-secondary:focus, .btn-secondary.focus {
    box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
}

.btn-secondary.disabled, .btn-secondary:disabled {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:not(:disabled):not(.disabled):active, .btn-secondary:not(:disabled):not(.disabled).active,
.show > .btn-secondary.dropdown-toggle {
    color: #fff;
    background-color: #545b62;
    border-color: #4e555b;
}

.btn-secondary:not(:disabled):not(.disabled):active:focus, .btn-secondary:not(:disabled):not(.disabled).active:focus,
.show > .btn-secondary.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
}

.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
}

.btn-success:focus, .btn-success.focus {
    box-shadow: 0 0 0 0.2rem rgba(72, 180, 97, 0.5);
}

.btn-success.disabled, .btn-success:disabled {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:not(:disabled):not(.disabled):active, .btn-success:not(:disabled):not(.disabled).active,
.show > .btn-success.dropdown-toggle {
    color: #fff;
    background-color: #1e7e34;
    border-color: #1c7430;
}

.btn-success:not(:disabled):not(.disabled):active:focus, .btn-success:not(:disabled):not(.disabled).active:focus,
.show > .btn-success.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(72, 180, 97, 0.5);
}

.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.btn-info:hover {
    color: #fff;
    background-color: #138496;
    border-color: #117a8b;
}

.btn-info:focus, .btn-info.focus {
    box-shadow: 0 0 0 0.2rem rgba(58, 176, 195, 0.5);
}

.btn-info.disabled, .btn-info:disabled {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,
.show > .btn-info.dropdown-toggle {
    color: #fff;
    background-color: #117a8b;
    border-color: #10707f;
}

.btn-info:not(:disabled):not(.disabled):active:focus, .btn-info:not(:disabled):not(.disabled).active:focus,
.show > .btn-info.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(58, 176, 195, 0.5);
}

.btn-warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-warning:hover {
    color: #212529;
    background-color: #e0a800;
    border-color: #d39e00;
}

.btn-warning:focus, .btn-warning.focus {
    box-shadow: 0 0 0 0.2rem rgba(222, 170, 12, 0.5);
}

.btn-warning.disabled, .btn-warning:disabled {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-warning:not(:disabled):not(.disabled):active, .btn-warning:not(:disabled):not(.disabled).active,
.show > .btn-warning.dropdown-toggle {
    color: #212529;
    background-color: #d39e00;
    border-color: #c69500;
}

.btn-warning:not(:disabled):not(.disabled):active:focus, .btn-warning:not(:disabled):not(.disabled).active:focus,
.show > .btn-warning.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(222, 170, 12, 0.5);
}

.btn-light {
    color: #212529;
    background-color: #f8f9fa;
    border-color: #f8f9fa;
}

.btn-light:hover {
    color: #212529;
    background-color: #e2e6ea;
    border-color: #dae0e5;
}

.btn-light:focus, .btn-light.focus {
    box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, 0.5);
}

.btn-light.disabled, .btn-light:disabled {
    color: #212529;
    background-color: #f8f9fa;
    border-color: #f8f9fa;
}

.btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active,
.show > .btn-light.dropdown-toggle {
    color: #212529;
    background-color: #dae0e5;
    border-color: #d3d9df;
}

.btn-light:not(:disabled):not(.disabled):active:focus, .btn-light:not(:disabled):not(.disabled).active:focus,
.show > .btn-light.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, 0.5);
}

.btn-dark {
    color: #fff;
    background-color: #343a40;
    border-color: #343a40;
}

.btn-dark:hover {
    color: #fff;
    background-color: #23272b;
    border-color: #1d2124;
}

.btn-dark:focus, .btn-dark.focus {
    box-shadow: 0 0 0 0.2rem rgba(82, 88, 93, 0.5);
}

.btn-dark.disabled, .btn-dark:disabled {
    color: #fff;
    background-color: #343a40;
    border-color: #343a40;
}

.btn-dark:not(:disabled):not(.disabled):active, .btn-dark:not(:disabled):not(.disabled).active,
.show > .btn-dark.dropdown-toggle {
    color: #fff;
    background-color: #1d2124;
    border-color: #171a1d;
}

.btn-dark:not(:disabled):not(.disabled):active:focus, .btn-dark:not(:disabled):not(.disabled).active:focus,
.show > .btn-dark.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(82, 88, 93, 0.5);
}


.badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.badge-success {
    color: #fff;
    background-color: #28a745;
}

.badge-error {
    color: #fff;
    background-color: #dc3545;
}

.blink {
    animation: blink-animation 1s steps(5, start) infinite;
    -webkit-animation: blink-animation 1s steps(5, start) infinite;
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
</style>
