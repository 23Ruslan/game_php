let game = {};
$(document).ready(function() {

    function initGame() {
        game.gameBoard = [ ['', '', ''], ['', '', ''], ['', '', ''] ];
        game.players = { 1: "O", 2: "X"};
        game.isClick = 0;
        game.toggleBoardClick = function() {
            if (this.isClick == 0) {
                this.enableBoardClick();
                this.isClick = 1;
                return;
            };
            this.disableBoardClic();
            this.isClick = 0;
        };

        game.getPlayer = function(k) {
            if (isNaN(k)) {
                return (k == game.players[1]) ? 1 : 2;
            };
            return (k == 1) ? game.players[1] : game.players[2];
        };

        game.getCurrentBoard = function() {
            $('td').each(function() {
                var $This = $(this),
                    player = $This.text();
                var position1 = parseInt($This.parent().attr('id').replace('r', '')),
                    position2 = parseInt($This.attr('class').replace('t', ''));
                if ('' != player) {
                    player = game.getPlayer(player);
                    game.gameBoard[position1][position2] = player;
                };
            });
            return this.gameBoard;
        };

        game.setPosition = function(position1, position2, player, obj) {
            this.gameBoard[position1][position2] = this.getPlayer(player);
            if (this.gameBoard[position1][position2] == 2) {
                $('td').each(function() {
                    if (position1 == $(this).parent().attr('id').replace('r', '') && position2 == $(this).attr('class').replace('t', '')) {
                        $(this).text(player);
                        return;
                    };
                });
            } else {
                obj.text(player);
            };
        };

        game.enableBoardClick = function() {
            $('td').unbind('click').click(function() {
                let $This = $(this);
                if ('' == $This.text()) {
                    var position1 = parseInt($This.parent().attr('id').replace('r', '')),
                        position2 = parseInt($This.attr('class').replace('t', '')),
                        currentBoard = $.extend(true, {}, game.getCurrentBoard());
                    game.disableBoardClick("Don\'t make a move while the bot is thinking.\nIf your game is over, click PLAY AGAIN.");
                    game.setPosition(position1, position2, game.players[1], $This);
                    $.ajax({
                        type: "POST",
                        url: "server/game/gameRunner.php",
                        data: {
                            position1: position1,
                            position2: position2,
                            gameBoard: currentBoard
                        },
                        success: function(msg) {
                            var jobj = JSON.parse(msg);
                            if (jobj.move) {
                                game.setPosition(jobj.move[0], jobj.move[1], game.players[2]);
                                game.enableBoardClick();
                            };

                            if (jobj.errorMessage) {
                                switch (jobj.errorMessage[0]) {
                                    case 1000:
                                        alert("There is no winner. Draw!");
                                        break;
                                    default:
                                        setTimeout(function() {
                                            victor = game.getPlayer(jobj.errorMessage);
                                            $.ajax({
                                                method: "POST",
                                                url: "server/game/getLevel.php",
                                                data: {
                                                    whoIsWinner: victor
                                                }
                                            })
                                            if (confirm("\"" + victor + "\" won, do you want to play again?")) {
                                                window.location.reload(true);
                                            };
                                            game.disableBoardClick("Game is over. The victor is: " + victor);
                                        }, 374);
                                };
                            };
                        }
                    });
                };
            });
        };
        game.disableBoardClick = function(text) {
            $('td').unbind('click').click(function() {
                alert(text);
            });
        };
    };
    initGame();
    game.enableBoardClick();
    $("#restart").mousedown(function() {
        $(this).addClass('clicked');
    }).mouseup(function() {
        window.location.reload(true);
    });
});