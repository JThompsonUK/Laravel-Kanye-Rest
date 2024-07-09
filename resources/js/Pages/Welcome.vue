<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div>
                    <div class="card-header mb-3">
                        <h1 class="card-title">Kanye Rest</h1>
                        <p>Getting random Kanye West quotes</p>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <div>
                                <button v-if="!dataFetched" @click="getData" class="btn btn-primary">Fetch Quotes</button>
                                <button v-else @click="refreshData" class="btn btn-primary">Fetch 5 New Quotes</button>
                            </div>
                            
                            <div>
                                <span v-if="message" class="mb-3">
                                    <h3 class="text-warning">{{ message }}</h3>
                                </span>

                                <span v-if="error">
                                    <h3 class="text-warning">{{ error }}</h3>
                                </span>
                            </div>
                            
                        </div>

                        <div v-for="(item, index) in items" :key="index">
                            <div class="border rounded p-3 my-3 bg-light ">
                                <q class="quotation">{{ item }}</q>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['appId'],
    data() {
        return {
            items: [],
            dataFetched: false,
            accessToken: null,
            error: null,
            message: null
        }
    },
    mounted() {
        this.getAccessToken();
    },
    methods: {
        getAccessToken() {
        axios.post('/api/get-token', { app_id: this.appId })
            .then(res => {
                this.accessToken = res.data.access_token;
            })
            .catch(error => {
                if (error.response && error.response.data) {
                    this.error = error.response.data.error;
                } else {
                    this.error = 'An unexpected error occurred.';
                }
            });
        },

        getData() {
            let config = {
                headers: {
                    'app-id': this.appId,
                    Authorization: `Bearer ${this.accessToken}`
                }
            };
            axios.get('/api/data', config)
                .then(res => {
                    this.items = res.data.items;
                    this.dataFetched = true;
                    this.message = res.data.message;
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        this.error = error.response.data.error;
                    } else {
                        this.error = 'An unexpected error occurred.';
                    }
                });
        },

        refreshData() {
            let config = {
                headers: {
                    'app-id': this.appId,
                    Authorization: `Bearer ${this.accessToken}`
                }
            };
            axios.get('/api/refresh-data', config)
                .then(res => {
                    this.items = res.data.items;
                    this.message = res.data.message;
                })
                .catch(error => {
                    console.error('Error fetching quotes', error);
                });
        }
    }
}
</script>

<style scoped>
.quotation{
    font-size: 1.5rem;
}
</style>
