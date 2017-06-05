layui.use(['jquery', 'flow'], function () {
    var $ = layui.jquery;
    var flow = layui.flow;

    //信息流
    flow.load({
        elem: '.blog-main-left', //指定列表容器
        isAuto: true,
        end: '没有更多了',
        mb: 200,
        done: function (page, next) {
            var pages;  //总页数
            var lis = [];   //html
            if (page == 1) {
                next(lis.join(''), page < 999999);
                return;
            }
            $.ajax({
                type: 'post',
                url: '/api/article/GetArticlesByFlow',
                contentType: 'application/json',
                data: JSON.stringify({ "currentIndex": page, "pageSize": 10, "type": 1 }),
                datatype: 'json',
                success: function (res) {
                    if (res.Success) {
                        lis.push(res.Data);
                        pages = res.SubCode;
                        next(lis.join(''), page < pages);
                    } else {
                        layer.msg('获取数据失败', { icon: 2 });
                    }
                },
                error: function (e) {
                    layer.msg(e.responseText);
                }
            });
        }
    });

    $(function () {

        var body = document.body;
        body.style.backgroundColor = "#393D49";
        body.style.overflow = "hidden";
        DrawCanvas();
    });

    function DrawCanvas() {
        var canvas = document.getElementById('canvas-banner');
        var ctx = canvas.getContext('2d');

        ctx.strokeStyle = (new Color(150)).style;

        var screenWidth = screen.width;
        console.log(screenWidth);
        canvas.width = window.innerWidth;
        console.log(canvas.width);
        canvas.height = window.innerHeight;
        console.log(canvas.height);
        dotCount = 200;
        dotRadius = 150;
        dotDistance = 80;
        // var dotCount = 20;
        // var dotRadius = 70;
        // var dotDistance = 70;
        // if (screenWidth >= 768 && screenWidth < 992) {
        //     dotCount = 170;
        //     dotRadius = 90;
        //     dotDistance = 60;
        //     canvas.height = window.innerHeight / 1;
        // } else if (screenWidth >= 992 && screenWidth < 1200) {
        //     dotCount = 120;
        //     dotRadius = 140;
        //     dotDistance = 70;
        //     canvas.height = window.innerHeight * 1 / 1;
        // } else if (screenWidth >= 1200 && screenWidth < 1700) {
        //     dotCount = 140;
        //     dotRadius = 150;
        //     dotDistance = 80;
        //     canvas.height = window.innerHeight * 1 / 1;
        // } else if (screenWidth >= 1700) {
        //     dotCount = 200;
        //     dotRadius = 150;
        //     dotDistance = 80;
        //     canvas.height = window.innerHeight;
        // }

        var mousePosition = {
            x: 50 * canvas.width / 100,
            y: 50 * canvas.height / 100
        };
        var dots = {
            count: dotCount,
            distance: dotDistance,
            d_radius: dotRadius,
            array: []
        };

        function colorValue(min) {
            return Math.floor(Math.random() * 255 + min);
        }

        function createColorStyle(r, g, b) {
            return 'rgba(' + r + ',' + g + ',' + b + ', 0.8)';
        }

        function mixComponents(comp1, weight1, comp2, weight2) {
            return (comp1 * weight1 + comp2 * weight2) / (weight1 + weight2);
        }

        function averageColorStyles(dot1, dot2) {
            var color1 = dot1.color,
                color2 = dot2.color;

            var r = mixComponents(color1.r, dot1.radius, color2.r, dot2.radius),
                g = mixComponents(color1.g, dot1.radius, color2.g, dot2.radius),
                b = mixComponents(color1.b, dot1.radius, color2.b, dot2.radius);
            return createColorStyle(Math.floor(r), Math.floor(g), Math.floor(b));
        }

        function Color(min) {
            min = min || 0;
            this.r = colorValue(min);
            this.g = colorValue(min);
            this.b = colorValue(min);
            this.style = createColorStyle(this.r, this.g, this.b);
        }

        function Dot() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;

            this.vx = -.8 + Math.random();
            this.vy = -.8 + Math.random();

            this.radius = Math.random() * 2;

            this.color = new Color();
        }

        Dot.prototype = {
            draw: function () {
                ctx.beginPath();
                ctx.fillStyle = "#fff";
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
                ctx.fill();
            }
        };

        function createDots() {
            for (i = 0; i < dots.count; i++) {
                dots.array.push(new Dot());
            }
        }

        function moveDots() {
            for (i = 0; i < dots.count; i++) {

                var dot = dots.array[i];

                if (dot.y < 0 || dot.y > canvas.height) {
                    dot.vx = dot.vx;
                    dot.vy = -dot.vy;
                }
                else if (dot.x < 0 || dot.x > canvas.width) {
                    dot.vx = -dot.vx;
                    dot.vy = dot.vy;
                }
                dot.x += dot.vx;
                dot.y += dot.vy;
            }
        }

        function connectDots1() {
            var pointx = mousePosition.x;
            for (i = 0; i < dots.count; i++) {
                for (j = 0; j < dots.count; j++) {
                    i_dot = dots.array[i];
                    j_dot = dots.array[j];

                    if ((i_dot.x - j_dot.x) < dots.distance && (i_dot.y - j_dot.y) < dots.distance && (i_dot.x - j_dot.x) > -dots.distance && (i_dot.y - j_dot.y) > -dots.distance) {
                        if ((i_dot.x - pointx) < dots.d_radius && (i_dot.y - mousePosition.y) < dots.d_radius && (i_dot.x - pointx) > -dots.d_radius && (i_dot.y - mousePosition.y) > -dots.d_radius) {
                            ctx.beginPath();
                            ctx.strokeStyle = averageColorStyles(i_dot, j_dot);
                            ctx.moveTo(i_dot.x, i_dot.y);
                            ctx.lineTo(j_dot.x, j_dot.y);
                            ctx.stroke();
                            ctx.closePath();
                        }
                    }
                }
            }
        }

        function drawDots() {
            for (i = 0; i < dots.count; i++) {
                var dot = dots.array[i];
                dot.draw();
            }
        }

        function animateDots() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            moveDots();
            connectDots1()
            drawDots();

            requestAnimationFrame(animateDots);
        }

        $('canvas').on('mousemove', function (e) {
            mousePosition.x = e.pageX;
            mousePosition.y = e.pageY;
        });

        $('canvas').on('mouseleave', function (e) {
            mousePosition.x = canvas.width / 2;
            mousePosition.y = canvas.height / 2;
        });

        createDots();
        requestAnimationFrame(animateDots);
    }

    window.addEventListener("resize", resizeCanvas, false);

    function resizeCanvas() {
        var canvas = document.getElementById('canvas-banner');
        canvas.width = window.document.body.clientWidth;
        canvas.height = document.body.clientHeight;
    }
});