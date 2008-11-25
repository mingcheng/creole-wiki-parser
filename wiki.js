var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

var callback = (function () {
        // 以后可能需要扩展，先用闭包
        return {
            success: function (req) {
                try {
                    var json = eval('(' + req.responseText + ')');
                    if (!json.error && json.response) {
                        console.info(json)
                        this.show(json.response);
                    }
                } catch(e) {
                this.error(e);
            }
        },

        failure: function (req) {
            this.error('获取数据错误');
        },

        show: function (data) {
            console.info(data);
            var vessel = Dom.get('result');
            if (!vessel) {
                var vessel = document.createElement('textarea');
                vessel.id = 'result';
                Event.on(vessel, 'click', function(e){
                    this.select();
                });
                Dom.insertAfter(vessel, 'wiki');
            }
            vessel.value = data;
        },

        error: function (message) {
           var box = Dom.get('error');
           if (!box) {
               var box = document.createElement('span');
               box.id = 'error';
               Dom.addClass(box, 'error');
               Event.on(box, 'click', function(e){
                    Dom.setStyle(this, 'display', 'none');
               });
               Dom.insertAfter(box, 'submit');
           }

           box.title = message;
           box.innerHTML = message;
           Dom.setStyle(box, 'display', '');
       },

       cache: false
}
})();

Event.on('form', 'submit', function (e) {
    var script = Dom.get('wiki');
    if (!script.value.length) {
        callback.error('请您复制/粘贴代码至输入框');
        script.focus();
    } else {
        YAHOO.util.Connect.setForm('form');
        YAHOO.util.Connect.asyncRequest('POST', this.action, callback);
    }
    Event.stopEvent(e);
});

YAHOO.util.Connect.startEvent.subscribe(function () {
    Dom.get('submit').disabled = 'disabled';
});

YAHOO.util.Connect.completeEvent.subscribe(function () {
    Dom.get('submit').disabled = '';
});
