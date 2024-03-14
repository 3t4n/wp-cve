(function($){
    var HpJs = {
        init: function() {
            this.wp_menu_item = "#menu-to-edit .menu-item";
            this.wp_delete_btn = ".item-delete";
            this.hp_delete_btn = "hp-menu-delete";

            this.delete_this_text = "x";
            this.delete_this_desc = "Delete this menu item";

            this.delete_all_text = "xx";
            this.delete_all_desc = "Delete this & all sub menu items";

            this.customRemove();
        },

        // remove current menu item and all sub menu items
        customRemove:function(){
            var self = this;
            $(self.wp_menu_item).each(function(){
                var this_menu = $(this);
                var item_controls = this_menu.find('.item-controls').find('.item-type');

                // delete this
                $( "<a/>", {
                    "class": self.hp_delete_btn,
                    text: self.delete_this_text,
                    title: self.delete_this_desc,
                    click:function(){
                        this_menu.find('.menu-item-settings').find(self.wp_delete_btn).trigger('click');
                        return false;
                    }
                }).insertBefore(item_controls);

                // delete all
                $( "<a/>", {
                    "class": self.hp_delete_btn,
                    text: self.delete_all_text,
                    title: self.delete_all_desc,
                    click:function(){
                        // get level of this menu item
                        var menu_level = self.menuLevel(this_menu);
                        // remove all children menu item
                        this_menu.nextUntil(".menu-item-depth-"+(menu_level)).remove();
                        // remove this menu item
                        this_menu.remove();
                        return false;
                    }
                }).insertBefore(item_controls);
            });
        },
        // extract level of this menu
        menuLevel:function(wp_menu_item){
            var tclass = wp_menu_item.attr("class");
            var level_class = tclass.match(/menu-item-depth-[0-9]+/);
            var level = level_class[0];
            level = parseInt(level.replace("menu-item-depth-",""));
            return level;
        }
    }
    HpJs.init();
})(jQuery);