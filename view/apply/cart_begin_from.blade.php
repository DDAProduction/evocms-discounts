<script>

    let cartBeginFromApply = {

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
            return ['cart'];
        },

        getCaption:function () {
            return '@lang('EvocmsDiscounts::main.start_from')';
        },
        getDescriptionForTable:function (value) {
            let output =  `<b>${this.getCaption()}: </b>`;


            if(value && value.cartBeginFrom){
                output += `${value.cartBeginFrom.from}, ${getOptionsTitle(value.cartBeginFrom.type,this.getOptions())}`;
            }

            return output;
        },

        addApplyView:function (parentId,values) {

            let formId = parentId+"_cart_begin_from";


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
            return $$(parentId+"_cart_begin_from").getValues();
        }


    };


    appliesManager.addApply('cartBeginFrom',cartBeginFromApply);
</script>