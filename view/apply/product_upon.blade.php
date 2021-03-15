<script>
    let ProductUponApply = {

        getOptions:function(){
            return [
                {
                    id:"pc",
                    value:"@lang('EvocmsDiscounts::main.pc')",
                },
                {
                    id:"sum",
                    value:"@lang('EvocmsDiscounts::main.sum')",
                }
            ];

        },
        getTypes:function(){
            return ['product'];
        },

        getCaption:function () {
            return '@lang('EvocmsDiscounts::main.discount_upon')';
        },

        getDescriptionForTable:function (value) {
            let output =  `<b>${this.getCaption()}: </b>`;

            if(value.productUpon){
                output += `${value.productUpon.from}, ${getOptionsTitle(value.productUpon.type,this.getOptions())}`;
            }

            return output;
        },

        addApplyView:function (parentId,values) {

            let formId = parentId+"_product_upon_apply";

            let obj = {
                id: formId,
                data:values,
                view:"form",
                cols:[

                    {
                        view:"text",
                        name:"from",
                    },
                    {
                        view:"select",
                        name:"type",
                        options:this.getOptions()
                    },

                ]
            };
            $$(parentId).addView(obj);
        },
        getData(parentId){
            return $$(parentId+"_product_upon_apply").getValues();
        }


    };


    appliesManager.addApply('productUpon',ProductUponApply);
</script>