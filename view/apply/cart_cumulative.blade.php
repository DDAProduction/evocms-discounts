<script>

    let cartCumulativeFromApply = {



        getTypes:function(){
            return ['cart'];
        },

        getCaption:function () {
            return '@lang('EvocmsDiscounts::main.discount_cumulative')';
        },
        getDescriptionForTable:function (value) {
            let output =  `<b>${this.getCaption()}: </b>`;
            //
            if(value.cartCumulative){
                output += `@lang('EvocmsDiscounts::main.from') ${value.cartCumulative.sum_from} `;
            }
            if(value.cartCumulative) {
                output += `@lang('EvocmsDiscounts::main.from') ${value.cartCumulative.sum_to}`;
            }

            return output;
        },

        addApplyView:function (parentId,values) {

            let formId = parentId+"_cart_cumulative";


            let obj = {
                id: formId,
                view: "form",
                data:values,
                elements:[{

                    rows:[

                        {
                            cols:[

                                {
                                    view:"text",
                                    name:"sum_from",
                                    label:"@lang('EvocmsDiscounts::main.sum_from')",
                                    labelPosition:"top"

                                },
                                {
                                    view:"text",
                                    name:"sum_to",
                                    label:"@lang('EvocmsDiscounts::main.sum_to')",
                                    labelPosition:"top"
                                }
                            ]

                        },
                        {
                            cols:[
                                {
                                    view:"datepicker",
                                    format:"%d.%m.%Y %H:%i:%s",
                                    name:"period_from",
                                    label:"@lang('EvocmsDiscounts::main.period_from')",
                                    labelPosition:"top"
                                },
                                {
                                    view:"datepicker",
                                    format:"%d.%m.%Y %H:%i:%s",
                                    name:"period_to",
                                    label:"@lang('EvocmsDiscounts::main.period_to')",
                                    labelPosition:"top"
                                }
                            ]

                        }
                    ]
                }]

            };

            $$(parentId).addView(obj);


        },
        getData(parentId){
            return $$(parentId+"_cart_cumulative").getValues();
        }


    };


    appliesManager.addApply('cartCumulative',cartCumulativeFromApply);
</script>