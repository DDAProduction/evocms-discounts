<script>
    let testStore = null;

    let productBeginFromApply = {

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
            return '@lang('EvocmsDiscounts::main.start_from')';
        },
        getDescriptionForTable:function (value) {
            let output =  `<b>${this.getCaption()}: </b>`;

            if(value.productBeginFrom){
                output += `${value.productBeginFrom.from}, ${getOptionsTitle(value.productBeginFrom.type,this.getOptions())}`;
            }

            return output;
        },

        addApplyView:function (parentId,values) {

            let formId = parentId+"_product_begin_from";


            let obj = {
                id: formId,
                view: "form",
                data:values,
                elements:[{

                    cols:[

                        {
                            view:"text",
                            name:"from",

                        },
                        {
                            view:"select",
                            name:"type",

                            options:this.getOptions()
                        }
                    ]

                }]

            };

            $$(parentId).addView(obj);


        },
        getData(parentId){
            return $$(parentId+"_product_begin_from").getValues();
        }


    };


    appliesManager.addApply('productBeginFrom',productBeginFromApply);
</script>