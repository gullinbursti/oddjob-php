/*! http://mths.be/placeholder v1.8.5 by @mathias */
(function(g,a,$){var f='placeholder' in a.createElement('input'),b='placeholder' in a.createElement('textarea');if(f&&b){$.fn.placeholder=function(){return this};$.fn.placeholder.input=$.fn.placeholder.textarea=true}else{$.fn.placeholder=function(){return this.filter((f?'textarea':':input')+'[placeholder]').bind('focus.placeholder',c).bind('blur.placeholder',e).trigger('blur.placeholder').end()};$.fn.placeholder.input=f;$.fn.placeholder.textarea=b;$(function(){$('form').bind('submit.placeholder',function(){var h=$('.placeholder',this).each(c);setTimeout(function(){h.each(e)},10)})});$(g).bind('unload.placeholder',function(){$('.placeholder').val('')})}function d(i){var h={},j=/^jQuery\d+$/;$.each(i.attributes,function(l,k){if(k.specified&&!j.test(k.name)){h[k.name]=k.value}});return h}function c(){var h=$(this);if(h.val()===h.attr('placeholder')&&h.hasClass('placeholder')){if(h.data('placeholder-password')){h.hide().next().show().focus().attr('id',h.removeAttr('id').data('placeholder-id'))}else{h.val('').removeClass('placeholder')}}}function e(){var l,k=$(this),h=k,j=this.id;if(k.val()===''){if(k.is(':password')){if(!k.data('placeholder-textinput')){try{l=k.clone().attr({type:'text'})}catch(i){l=$('<input>').attr($.extend(d(this),{type:'text'}))}l.removeAttr('name').data('placeholder-password',true).data('placeholder-id',j).bind('focus.placeholder',c);k.data('placeholder-textinput',l).data('placeholder-id',j).before(l)}k=k.removeAttr('id').hide().prev().attr('id',j).show()}k.addClass('placeholder').val(k.attr('placeholder'))}else{k.removeClass('placeholder')}}}(this,document,jQuery));

$(function() {
    $('input,textarea').placeholder();
});

/* Actions */
(function() {
    var typeahead,
        birthdays,
        FRIENDS,
        qs = (function(l) {
                var result = {}, queryString = l.substring(1), re = /([^&=]+)=([^&]*)/g, m;
                while (m = re.exec(queryString)) {
                    result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
                }
                
                return result;
        })(window.location.search);
    
    window.fbLoaded = function() {
        setTimeout(function() {
            updateScore();
            $.get('/ajax/clear_requests/');
        },1000);
    }
    
    function createCookie(name,value,days) {
    	if (days) {
    		var date = new Date();
    		date.setTime(date.getTime()+(days*24*60*60*1000));
    		var expires = "; expires="+date.toGMTString();
    	}
    	else var expires = "";
    	document.cookie = name+"="+value+expires+"; path=/";
    }
    
    function readCookie(name) {
    	var nameEQ = name + "=";
    	var ca = document.cookie.split(';');
    	for(var i=0;i < ca.length;i++) {
    		var c = ca[i];
    		while (c.charAt(0)==' ') c = c.substring(1,c.length);
    		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    	}
    	return null;
    }
    
    function eraseCookie(name) {
    	createCookie(name,"",-1);
    }
    
    function dialog(title, text) {
        $(".dialog").remove();
        var data = {title: title, text: text};
        var html = Mustache.to_html($("#dialogTemplate").html(), data);
        FB.Canvas.getPageInfo(function(page) {
            var top = Math.max(100, parseInt(page.scrollTop) + parseInt(page.offsetTop));
            $(html).css({top: top}).appendTo(document.body).find(".dialog_buttons input").click(function() {
                $(".dialog").remove();
            });
        });
    }
    
    function ask(title, text, callback, yes, no) {
        $(".dialog").remove();
        var yes = yes || 'Okay';
        var no = no || 'Cancel';
        var data = {title: title, text: text, yes: yes, no: no};
        var html = Mustache.to_html($("#askTemplate").html(), data);
        FB.Canvas.getPageInfo(function(page) {
            var top = Math.max(100, parseInt(page.scrollTop) + parseInt(page.offsetTop));
            $(html).css({top: top}).appendTo(document.body).find(".dialog_buttons input.silver").click(function() {
                $(".dialog").remove();
            }).end().find(".yes").click(function() {
                $(".dialog").remove();
                if(callback) {
                    callback();
                }
            });
        });
    }
    
    function reset() {
        $('#friend-finder').data('reset')();
        /* Reset gift selector */
        $(".gifts .cancel").click();
        $("textarea[name='message']").val('');
        $("input,textarea,select").prop("disabled", false);
        $("#page_count").show();
        $("select[name='private']").val(0);
        $("#all_friends").hide();
        $("#select_friend").show();
    }
    
    /* Called after if a gift is sent & saved */
    function success(data) {
        FB.Canvas.scrollTo(0,0);
        reset();
        dialog("Gift Sent", 'Your gift, <b>' + data.giftName + '</b>, was sent! Make sure to like our fan page! <div class="divider mtl mbl"></div><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Ffreegifts.sgn&amp;send=false&amp;layout=standard&amp;width=475&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80&amp;appId=2415466380" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:475px; height:80px;" allowTransparency="true"></iframe>');
        updateScore();
    }
    
    $(function() {
        $("#invite").click(function() {
            FB.ui({method: 'feed', 'link': 'http://apps.facebook.com/freegifts', picture: 'http://gifts.64px.com/static/images/fg.gif', name: "You're Invited", description: _USER.first_name + " invited you to send gifts on Facebook!"});
        });
    });
    
    function updateScore() {
        if(FB) {
            FB.api('me/scores', function(res) {
                for(var i in res.data) {
                    if(res.data[i].application.id == app_id) {
                        $('#score').html('Your Free Gifts score is <b>' + res.data[i].score + '</b>, send more gifts to raise your score.');
                    }
                }
            });
        }
    }
    
    function findBirthdays(friends) {
        FRIENDS = friends;
        var date = new Date(),
            month = date.getMonth() + 1,
            today = [((month < 10) ? "0" + month : month), "/", ((date.getDate() < 10) ? "0" + date.getDate() : date.getDate())].join("");
        var bdays = _.select(friends, function(friend) {
            return (friend.birthday && _(friend.birthday).startsWith(today));
        });
        
        var monthPadded = (month < 10) ? "0" + month : month;
        var monthBdays = _.select(friends, function(friend) {
            return (friend.birthday && _(friend.birthday).startsWith(monthPadded));
        });
        
        if(monthBdays.length) {
            $("#welcome ul").append("<li><b>" + monthBdays.length + "</b> of your friends have birthdays this month, don't miss any!</li>");
        }
        
        if(bdays.length) {
            for(var i in bdays) {
                $("#welcome ul").append("<li>It's <a class='person pointer' data-id='" + bdays[i].id +  "'><b>" + bdays[i].name +  "'s</b></a> birthday today, click <a class='person pointer' data-id='" + bdays[i].id +  "'>here</a> to send a birthday gift!</li>");
            }
        }
        
        if(bdays.length) {
            var plural = (bdays.length > 1) ? "s" : "",
                text = bdays.length + " birthday" + plural + " today";
            $("#birthdaysLink").text(text);
        }
        
        birthdays = bdays;
        
        if(!(readCookie('visited_today') == 'yes')) {
            $("#birthdaysLink").click();
            createCookie('visited_today', 'yes', 0.2);
        }
    }
    
    function verify() {
        try {
            var gift = $("input[name='gift']").val();
            return ($("input[name='friends']").val().length && gift);
        } catch(e) { return false; }
    }
    
    $.getJSON('https://graph.facebook.com/me/friends/?access_token=' + access_token + '&callback=?&fields=name,birthday,username', function(friends) {
        try {
            /* add user to the dropdown */
            friends.data.push({id: _USER.uid, text: _USER.name, name: _USER.name});
            /* add all friends to dropdown */
            friends.data.push({id: _USER.uid, text: 'All Friends', name: 'All Friends', picture: '/static/images/fg.gif'});
            $('#friend-finder').friendFinder(friends);
            findBirthdays(friends.data);
            if(qs['to']) {
                $("<div class='person' />").appendTo(document.body).data({id: qs['to']}).click().remove();
            }
        } catch(e) {
            dialog("Problem", "We're sorry, but there is a problem. Please try again later! Check out our <a href='http://facebook.com/freegifts.sgn'>support page</a> for updates.");
        }
    });
    
    /* Gift paging */
    $(function() {
        var pager = $("#gift_pager"),
            gifts = _GIFTS,
            up = pager.find("#page_up"),
            down = pager.find("#page_down"),
            reload = pager.find("#page_reload"),
            page = 1,
            page_count = $("#page_count"),
            gifts_per_page = $(".gifts .gift").length,
            showGifts = function(gifts) {
                $(".gifts .gift").remove();
                var html = Mustache.to_html($("#giftsTemplate").html(), {gifts: gifts});
                $(".gifts .selector").prepend(html);
            },
            reloader = function() {
                var start = (page - 1) * gifts_per_page,
                    pageGifts = gifts.slice(start, start + gifts_per_page);
                showGifts(pageGifts);
                $("#gift_pager").show();
                page_count.show();
            },
            pageUp = function(e) {
                e.preventDefault();
                return (page == 1) ? false : (function() {
                    var start = (page-2) * gifts_per_page,
                        pageGifts = gifts.slice(start, start + gifts_per_page);
                        showGifts(pageGifts);
                        page--;
                        page_count.text("Page " + page + " of " + page_count.data("pages"));
                })();
            },
            pageDown = function(e) {
                e.preventDefault();
                return (page == Math.ceil(gifts.length / gifts_per_page)) ? false : (function() {
                    var start = (page) * gifts_per_page,
                        pageGifts = gifts.slice(start, start + gifts_per_page);
                        showGifts(pageGifts);
                        page++;
                        page_count.text("Page " + page + " of " + page_count.data("pages"));
                })();
            }
            
        up.click(pageUp).bind("selectstart", false);
        down.click(pageDown).bind("selectstart", false);
        reload.click(reloader);
    });
    
    /* Setup Gift Selector */
    $(function() { 
        var gifts = $(".gifts"),
            selector = gifts.find(".selector"),
            selected = gifts.find(".selected"),
            title = gifts.find("#gift_title"),
            change = gifts.find(".cancel"),
            pager = $("#gift_pager"),
            page_count = $("#page_count"),
            gift_id = gifts.find("input[name='gift_id']"),
            value = gifts.find("input[name='gift']"),
            select = function() {
                var data = $(this).data();
                data.src = $(this).attr("src");
                value.val(data.src);
                gift_id.val(data.id);
                var html = Mustache.to_html($("#selectedTemplate").html(), data);
                selector.hide();
                pager.hide();
                selected.html(html).show();
                gifts.addClass("giftSelected");
                page_count.hide();
                FB.XFBML.parse(gifts[0]);
            }
            
        gifts.delegate(".gift", "mouseenter", function() {
            $(this).addClass("hover").stop().siblings().stop().fadeTo("fast", 0.5).end().fadeTo("fast", 1);
            title.text($(this).data("title"));
        }).delegate(".gift", "mouseleave", function() {
            $(this).removeClass("hover").siblings().andSelf().stop().fadeTo("fast", 1);
            title.html("&nbsp;");
        }).delegate(".gift", "click", select);
        
        change.click(function() {
            selector.show();
            selected.html('').hide();
            gifts.removeClass("giftSelected");
            value.val('');
            pager.show();
            page_count.show();
        });
    });
    
    /* Birthdays */
    $("#birthdaysLink").live("click", function() {
        if(birthdays && birthdays.length) {
            var html = Mustache.to_html($("#birthdaysTemplate").html(), {bdays: birthdays});
            dialog("Birthdays Today", html);
        }
    });
    
    /* Filter Gifts */
    function filterGifts(filter) {
        $(".gifts .cancel").click();
        if(filter == "all") {
            $("#page_reload").click();
            return;
        }
        var matches = _.select(_GIFTS, function(gift) {
            return (gift['Attributes'].description.toLowerCase().indexOf(filter) > -1 || gift['Attributes'].title.toLowerCase().indexOf(filter) > -1);
        });
        
        $("#gift_pager").hide();
        $(".gifts .gift").remove();
        var html = Mustache.to_html($("#giftsTemplate").html(), {gifts: matches});
        $(".gifts .selector").prepend(html);
    }
    
    /* Filtering */
    $(function() {
        $(".filter").click(function() {
            var filter = $(this).text().toLowerCase();
            filterGifts(filter);
            $("#search").val('');
        });
        
        var search = _.throttle(function() {
            var filter = $(this).val().toLowerCase();
            if(filter == '') {
                filterGifts('all');
            } else if(filter == 'bday' || filter == 'navidad') {
                filterGifts('birthday');
            } else {
                filterGifts(filter);
            }
        }, 500);
        $("#search").bind('keyup', search).bind('keydown', function(e) {
            if(e.which == 13) {
                e.preventDefault();
            }
        });
    });
    
    /* Pre select a person */
    $(".person").live("click", function() {
        var to = $(this).data('id');
        if(!$("#friend-finder").length) {
            window.location.href = '/?to=' + to;
            return;
        }
        $("#friend-finder").data('selectId')(to);
        $(".dialog").hide();
    });
    
    /* Ticker */
    $(function() {
        var ticker = $("#welcome ul");
        setInterval(function() {
            ticker.children().first().slideUp("fast", function() {
                $(this).remove().show().appendTo(ticker);
            });
        }, 7000);
    });
    
    /* Gift form submit */
    var sending = false;
    $(function() {
        $("#send").submit(function(e) {
            e.preventDefault();
            
            /* Prevent multiple sends */
            if(sending) { return; }
            sending = true;
            
            /* Check if they selected a friend a gift */
            if(!verify()) { 
                dialog("Whoops!", "You need to select a friend & a gift first!<br /><br />Think you did all this, and still can't send? Contact us <a href='http://facebook.com/freegifts.sgn' target='_BLANK'>here</a>");
                sending = false;
                return;
            }
            
            /* Get the form data */
            var data = $(this).serializeObject();
                data.private = (data.private) ? parseInt(data.private) : 0;
                data.giftName = $("#gt").text();
                data.friends = (data.friends && data.friends.length) ? data.friends.split(',').slice(0,20) : false;
            
            /* Sending privately */
            if(data.private == 1) {
                FB.ui({
                     method: 'send',
                     to: data.friends.join(','),
                     display: 'iframe',
                     name: data.giftName,
                     link: app_url,
                     picture: data.gift,
                     description: ((data.message) ? data.message : "A Free Gift from " + _USER.first_name)
                }, function(res) {
                    sending = false;
                    if(res != null) {
                        success(data);
                    }
                });
            /* Sending publicly */
            } else {
                var batch = [], url, e = encodeURIComponent, i;
                url = '/feed?message=' + e(data.message)
                    + '&name=' + e(data.giftName)
                    + '&caption=' + e("↪ click for more gifts")
                    + '&description=' + e("A Free Gift from " + _USER.first_name)
                    + '&picture=' + e(data.gift)
                    + '&link=' + e(app_url)
                    + '&actions=' + e('[{"name":"Send a Gift","link":"http://apps.facebook.com/freegifts/"}]')
                
                for(i in data.friends) {
                    batch.push({method: 'post', relative_url: data.friends[i] + url});
                }
                
                /* Batch post */
                FB.api('/', 'POST', {
                    batch: batch
                }, function(res) {
                    var posted = [], id;
                    for(var i in res) {
                        id = res[i].body.match(/([0-9]+)_/);
                        if(id) {
                            posted.push(id[1]);
                        }
                    }
                    var failed = _.filter(data.friends, function(id) {
                        return !_.contains(posted, id);
                    });
                    
                    /* If any failed, try sending a request instead */
                    if(failed.length) {
                        FB.ui({method: 'apprequests', to: failed.join(','),
                               message: _USER.first_name + ' sent you ' + data.giftName + ' on Free Gifts!'},
                              function() {
                                sending = false;
                                success(data);
                        });
                    } else {
                        sending = false;
                        /* Add auto liking? */
                        success(data);
                    }
                });
            }
            
            /* Save the gift on our end */
            $.post("/ajax/send/", data);
        });
    });
    
    /* Button visual actions */
    $(".button").live("mousedown", function() {
        $(this).addClass("downstate");
    }).live("mouseup mouseout", function() {
        $(this).removeClass("downstate");
    });
    
})();

/* For ajax form submission */
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
