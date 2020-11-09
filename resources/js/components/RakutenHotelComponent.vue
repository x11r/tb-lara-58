<template>
    <div class="app">
        {{ showAreaMiddle }}
        <div v-for="(largeClass, index1) in areas.areaClasses">
            index1:{{ index1 }}
            <div v-if="largeClass[0].largeClass">
                largeClassName:{{ largeClass[0].largeClass[0].largeClassName }}
                ({{ largeClass[0].largeClass[0].largeClassCode }})
                <div
                    v-for="(middleClasses, index2) in largeClass[0].largeClass[1].middleClasses"
                    class="middleClass"
                    v-bind:id="'middleClass-' + index2 "

                >
                    {{ middleClasses.middleClass[0].middleClassName }}
                    ({{ middleClasses.middleClass[0].middleClassCode }})
                    index2:{{ index2 }}
                    <div class="row">
                        <div>
                            <button
                                @click="switchAreaMiddle(index2)"
                            >
                                表示 {{ index2 }}
                            </button>
                        </div>
                    </div>
                    <div
                        v-for="(smallClasses, index3) in middleClasses.middleClass[1].smallClasses"
                        class="smallClass"
                        v-bind:id="'smallClass-' + index3"
                        v-show="showAreaMiddle[index2]"
                    >
                        {{ smallClasses.smallClass[0].smallClassName }}
                        ({{ smallClasses.smallClass[0].smallClassCode }})
                        <div v-if="smallClasses.smallClass[1]">
                            <div v-for="(detailClass, index4) in smallClasses.smallClass[1].detailClasses">
                                {{ detailClass.detailClass.detailClassName }}
                                ({{ detailClass.detailClass.detailClassCode }})
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
    name: "RakutenHotelComponent.vue",
    data() {
        return {
            areas: [],
            showAreaMiddle: []
        }
    },
    async mounted() {
        this.setArea();
    },
    methods: {
        setArea: function() {
            let url = '/api/v1/rakuten/area';
            axios.get(url)
            .then(response => {
                this.areas = response.data;

                // エリア表示フラグ
                let showAreaMiddle = [];
                for (let i1 in response.data.areaClasses['largeClasses'][0].largeClass[1].middleClasses) {
                    showAreaMiddle[i1] = false;
                }

                // 初期表示するものは上書きする
                showAreaMiddle[0] = true;

                // dataに代入する
                this.showAreaMiddle = showAreaMiddle;
            })
        },
        switchAreaMiddle: function(areaIndex) {
            let showAreaMiddle = [];
            for (let i1 in this.showAreaMiddle) {
                showAreaMiddle[i1] = i1 === parseInt(areaIndex) ? true : false;
                if (parseInt(i1) === parseInt(areaIndex)) {
                    console.log(i1 + ' :true');
                } else {
                    console.log(i1 + ' :false');
                }

            }

            this.showAreaMiddle = showAreaMiddle;
        }
    }
}
</script>
<style scoped>
div {
    border: 1px solid #ccc;
    margin-left: 1em;
}
.largeClass {
    backgroundColor:#ddd;
    color: red;
}

.middleClass {
    background-color: #bbb;
}
.smallClass {
    background-color: #999;
}
</style>
