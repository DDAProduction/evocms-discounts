<script>
    let ProductForEachApply = {

        getOptions:function(){
          return [
              {
                  id:"pc",
                  value:"@lang('EvocmsDiscounts::main.pc')",
              }
          ];
        },

        getTypes:function(){
            return ['product'];
        },

        getCaption:function () {
            return '@lang('EvocmsDiscounts::main.discount_for_each')';
        },

        getDescriptionForTable:function (value) {
            let output =  `<b>${this.getCaption()}: </b>`;

            if(value.productForEach){
                output += `${value.productForEach.from}, ${getOptionsTitle(value.productForEach.type,this.getOptions())}`;
            }

            return output;
            },

        addApplyView:function (parentId,values) {

            let formId = parentId+"_product_for_each";


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

            return $$(parentId+"_product_for_each").getValues();
        }

    };


    appliesManager.addApply('productForEach',ProductForEachApply);
</script>