function setImg(fallos) {
                var url = "";
                switch (fallos) {
                    case 0:
                        document.write('<img src="../img/ahorcado1.png"/>');
                        break;
                    case 1:
                        document.write('<img src="../img/ahorcado2.png"/>');
                        break;
                    case 2:
                        document.write('<img src="../img/ahorcado3.png"/>');
                        break;
                    case 3:
                        document.write('<img src="../img/ahorcado4.png"/>');
                        break;
                    case 4:
                        document.write('<img src="../img/ahorcadoLose.png"/>');
                        break;
                    default:
                        break;
                }
                return url;
            }
