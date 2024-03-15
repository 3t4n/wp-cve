(function() {  
    tinymce.create('tinymce.plugins.pricing_table', {  
        init : function(ed, url) {  
            ed.addButton('pricing_table', {  
                title : 'Add A Pricing Table',  
                image : url+'/icons/pricing_table.png',  
                onclick : function() {  
                    ed.selection.setContent('[pricing_table title="Pricing Table" columns="3"]<br><br>[pricing_column first_column="1" title="Your Title" price="15" currency_symbol="$" plan="per monthly" features="feature 1, feature 2, feature 3, ext..." button_text="Sign Up" button_url="http://kopatheme.com"]<br><br>[pricing_column special="1" title="Your Title" price="15" currency_symbol="$" plan="per monthly" features="feature 1, feature 2, feature 3, ext..." button_text="Sign Up" button_url="http://kopatheme.com"]<br><br>[pricing_column title="Your Title" price="15" currency_symbol="$" plan="per monthly" features="feature 1, feature 2, feature 3, ext..." button_text="Sign Up" button_url="http://kopatheme.com"]<br><br>[/pricing_table]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('pricing_table', tinymce.plugins.pricing_table);  
})();
