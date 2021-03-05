;
((window, document) => {
    /*----------------------------------------------------------*/
    /*  scroll
    /*----------------------------------------------------------*/
    // Scroll function
    let bodyClass = () => {
        if(window.pageYOffset > 0){
            document.body.classList.add('scrolled')
        }else{
            document.body.classList.remove('scrolled')
        }
    }
    window.addEventListener('scroll', bodyClass, false)
    window.addEventListener('DOMContentLoaded', bodyClass, false)

    if(lightningOpt.header_scrool){
        let body_class_timer = false;
        let body_class_lock = false;
        // siteHeader.offsetHeight
        let header_scrool_func = ()=>{ 
            if( ! body_class_lock && window.pageYOffset > 160 ){
                document.body.classList.add('header_scrolled')
            }else{
                document.body.classList.remove('header_scrolled')
            }
        }

        let remove_header = (e) => {
            document.body.classList.remove('header_scrolled')
            window.removeEventListener('scroll', header_scrool_func)
            if (body_class_timer !== false) {
                clearTimeout(body_class_timer)
            }
            body_class_lock = true
            body_class_timer = setTimeout(()=>{
                window.addEventListener('scroll', header_scrool_func, true)
                body_class_lock = false
            }, 2000);
        }

        window.addEventListener('DOMContentLoaded', () => {
            Array.prototype.forEach.call(
                document.getElementsByTagName('a'),
                (elem) => {
                    let href = elem.getAttribute('href')
                    if(!href || href.indexOf('#') != 0) return;
                    if (['tab', 'button'].indexOf(elem.getAttribute('role')) > 0) return;
                    if (elem.getAttribute('data-toggle')) return;
                    if (elem.getAttribute('carousel-control')) return;
                    elem.addEventListener('click', remove_header)
                }
            )
        });

        window.addEventListener('scroll', header_scrool_func, true)
        window.addEventListener('DOMContentLoaded', header_scrool_func, false)
    }

   /*-------------------------------------------*/
    /*  iframeのレスポンシブ対応
    /*-------------------------------------------*/
    function iframe_responsive() {
        Array.prototype.forEach.call(
            document.getElementsByTagName('iframe'),
            (i) => {
                let iframeUrl = i.getAttribute('src')
                if(!iframeUrl){return}
                // iframeのURLの中に youtube か map が存在する位置を検索する
                // 見つからなかった場合には -1 が返される
                if (
                    (iframeUrl.indexOf("youtube") >= 0) ||
                    (iframeUrl.indexOf("vimeo") >= 0) ||
                    (iframeUrl.indexOf("maps") >= 0)
                ) {
                    var iframeWidth = i.getAttribute("width");
                    var iframeHeight = i.getAttribute("height");
                    var iframeRate = iframeHeight / iframeWidth;
                    var nowIframeWidth = i.offsetWidth
                    var newIframeHeight = nowIframeWidth * iframeRate;
                    i.style.maxWidth = '100%'
                    i.style.height = newIframeHeight + 'px'
                }
            }
        );
    }

    window.addEventListener('DOMContentLoaded',iframe_responsive)
    let timer = false;
    window.addEventListener('resize', ()=>{
        if (timer) clearTimeout(timer);
        timer = setTimeout(iframe_responsive, 200);
    })


})(window, document);
