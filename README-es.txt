DIGUI PARA MOODLE
=================

Hemos escrito un nuevo m�dulo para Moodle 3.1. Hemos enviado los archivos 
necesarios a la direcci�n https://github.com/fermitanio/moodle-activity_digui. 
El archivo comprimido digui.zip contiene la estructura completa del m�dulo, e 
incluye archivos readme. Para utilizar Digui, el archivo digui.zip debe ser 
descomprimido en el directorio mod de Moodle.

Puede ver v�deos sobre Digui en la direcci�n 
https://www.youtube.com/channel/UCyxlh0kmnfS207vVWPURhdQ. 

Para cualquier cuesti�n sobre Digui, por favor escriba a la direcci�n de 
email, fermitanio@hotmail.com.

Gracias.


REQUISITOS DEL SISTEMA
======================

* Digui debe ser instalado en Moodle 2.4.11, o versiones superiores, junto con
  la base de datos MySQL. Digui no ha sido probado con la base de datos 
  PostgreSQL.
  
* Digui admite los siguientes navegadores: Chrome, Firefox, Opera y Safari.
  Digui no ha sido probado en los navegadores Explorer y Edge.

* Para funcionar, Digui necesita que JavaScript est� activado en el navegador.


INSTALACI�N R�PIDA
==================

   Aqu� est� la informaci�n b�sica sobre el proceso de instalaci�n, que suele 
   llevar unos minutos:

   1� El m�dulo est� en un archivo comprimido llamado "Digui.zip". Debemos 
descomprimir este archivo en la carpeta "moodle/mod/".

   2� Identificarnos en Moodle como administrador.

   3� Seguir las instrucciones.
   
   Para desinstalar el m�dulo, por favor sigue los siguientes pasos:

   1� Acceder a Moodle como administrador.
   
   2� En Moodle, pulsar sobre "Administraci�n del sitio", luego en 
   "Extensiones", luego en "M�dulos de actividad", luego en "Gestionar 
   actividades".
   
   3� Buscar el m�dulo en la lista, y presionar en "Desinstalar".
   

   
QU� HACE ESTA ACTIVIDAD
=======================

   Muchas veces, el profesor entrega a sus estudiantes p�ginas de texto, para 
   que las lean. Sin embargo, la mayor�a de las veces los estudiantes no leer�n
   el documento completo, sino una selecci�n. Digui incluye un marcador digital
   que permite subrayar secciones de documentos. Con la actividad Digui, usted
   puede subrayar texto y guardar el resultado en un archivo. Use la 
   herramienta de marcado para encontrar y marcar texto importante en una 
   p�gina web, como si se tratase de un papel. Usted puede enfatizar: 
   
    * una frase o palabra que contiene una idea importante,
    * citas,
    * estad�sticas,
    * t�rminos especiales,
    * datos �tiles o importantes,
    * ejemplos o enlaces a otras ideas.
	   
   Utilizar marcadores para resaltar texto es una gran idea. Subrayar es 
   conveniente cuando los res�menes son importantes para entender el
   material. Subrayar le ayuda a pensar cr�ticamente y a formular sus propias
   respuestas al texto. Adem�s, subrayar puede mejorar la retenci�n del texto
   seleccionado, porque ayuda a concentrar la atenci�n a hechos individuales. 
  
  
DESCRIPCI�N DE DIGUI
====================

Digui es un programa para subrayar texto, hacer anotaciones y exportar los 
resultados a un archivo.


BENEFICIOS DE UTILIZAR DIGUI
============================

Digui es una herramienta para el aprendizaje y el trabajo colaborativo. Aunque
Digui es una actividad para Moodle, el profesor que lo utilice puede planificar
las actividades que dese�, de acuerdo con las caracter�sticas de su asignatura.
As� por ejemplo, con Digui el profesor podr� dise�ar las siguientes 
actividades:

- Sus alumnos deber�n responder a preguntas, subrayando las respuestas en un 
texto. Actividad v�lida para cualquier asignatura.
- Evaluar la comprensi�n lectora de los alumnos, subrayando palabras o frases
clave. Actividad v�lida para cualquier asignatura.
- Realizar res�menes o s�ntesis de documentos. Actividad v�lida para cualquier 
asignatura.
- Se�alar hiperv�nculos y otros elementos del hipertexto. Actividad v�lida para 
una asignatura de inform�tica.
- Se�alar sustantivos y otros elementos gramaticales. Actividad v�lida para 
una asignatura de lengua y literatura.
- Se�alar tipos de datos, literales, y otros elementos de un lenguaje, para una
asignatura de programaci�n inform�tica.
- Se�alar consultas, comandos, y otras �rdenes y elementos de un lenguaje, para
una asignatura de bases de datos.
- Etc.


FUNCIONALIDADES B�SICAS
=======================

- M�s de un usuario pueden subrayar el mismo bloque de texto, mostr�ndose en 
color gris.
- Admite la importaci�n de archivos epub, html y txt, y la exportaci�n de 
res�menes a archivos txt. 
- Admite hasta 11 usuarios por cada digui, es decir, 11 usuarios como m�ximo 
pueden editar el mismo digui.
- Si una p�gina est� siendo subrayada por un usuario, el siguiente usuario 
que quiera subrayar la misma p�gina debe esperar a que el primer usuario 
abandone la p�gina, o que el primer usuario est� inactivo durante 5 minutos.
- Admite los modos de Moodle individual o colaborativo.
- Admite los grupos de Moodle sin grupos, grupos visibles y grupos separados.


INTRODUCCI�N A LA INTERFAZ
==========================

La interfaz de Digui est� organizada en cuatro fichas: Ver, Editar, Evaluar y 
Exportar. Las fichas Ver y Editar muestran una p�gina de texto, y el usuario
puede visualizar cualquier otra p�gina de texto, usando los hiperv�nculos
inferiores. La ficha Evaluar es para uso exclusivo de usuarios con el rol de
profesor, y no ser� visualizada si el usuario activo tiene el rol de 
estudiante. Los profesores pueden usar la ficha Evaluar para poner una nota a
sus estudiantes. Finalmente, cualquier usuario puede guardar el texto subrayado
en un archivo de texto, mediante la ficha Exportar


MANUAL DEL USUARIO
==================

Antes de subrayar algo usted debe enviar a Moodle el archivo que contiene el 
texto a subrayar. Este archivo es �nico, y puede ser un archivo de texto plano 
(.txt) o una p�gina web (.html). Despu�s de esta etapa, Digui extrae el texto e
ignora las im�genes y otros elementos sup�rfluos del archivo. 

Desde este momento, usted puede seleccionar y subrayar texto. Para ello, debe 
abrir la solapa Editar y comenzar a arrastrar el rat�n sobre la pantalla. 

Un mismo texto puede ser subrayado varias veces, por diferentes usuarios. En 
este caso el color del texto es gris, m�s oscuro cuantos m�s usuarios hayan 
subrayado ese texto.

Para ver los resultados debe abrir la solapa Ver. Si sus estudiantes est�n
separados en grupos, usted puede configurar Digui para reflejar esta 
organizaci�n. Si Digui est� configurado en grupos separados, la solapa Ver
muestra s�lo el resultado de los usuarios que est�n en el grupo del usurio 
actual. Si Digui est� configurado en grupos visibles, la solapa Ver muestra
el resultado de los grupos que usted elija.

Para guardar el texto subrayado en un archivo, debe utilizar la solapa 
Exportar. 

S�lo los profesores pueden evaluar al resto de usuarios, mediante la solapa 
Calificaciones. Sin embargo, despu�s de que un profesor haya evaluado a todos
los usuarios, los estudiantes podr�n ver sus calificaciones mediante la solapa
Calificaciones.


C�MO FUNCIONA DIGUI
===================

El primer paso para usar Digui, es identificarse en Moodle como administrador, 
y a�adir una actividad Digui. Los campos "Nombre del Digui" y "T�tulo del 
libro" son obligatorios. Adem�s, el usuario puede establecer el modo del digui
como individual o como colaborativo. Si el modo es individual, cada p�gin del 
digui podr� ser subrayada por un �nico usuario. Si el modo es colaborativo, 
cada p�gina del digui podr� ser subrayada por varios usuarios.

Despu�s de que el usuario cree la actividad Digui, el segundo paso es enviar el
texto a subrayar. La fuente del texto puede ser un archivo epub, un archivo 
html o un archivo txt. El usuario puede enviar s�lo un archivo. Durante el 
proceso de env�o, Digui convierte el archivo fuente en texto con formato (si
el formato del archivo es epub o html), y divide el archivo en varias p�ginas
de texto, de unos 4000 caracteres aproximadamente cada p�gina. Im�genes y otros
elementos son descartados. Las p�ginas son guardadas en el campo 
"cachedcontent" de la tabla mdl_digui_pages. Este campo no puede ser 
modificado, es de s�lo lectura.

Despu�s de enviar el archivo, el usuario puede subrayar el texto usando la 
ficha Editar. El usuario puede subrayar bloques de texto, arrastrando el rat�n
sobre el texto. Si el modo del digui es individual, la ficha Editar muestra 
las marcas del usuario actual, �nicamente. Si el modo del digui es 
colaborativo, la ficha Editar muestra las marcas de los usuarios que han 
subrayado la p�gina actual.

Cuando un usuario hace click sobre una actividad de Digui por primera vez,
este usuario se a�ade a la tabla digui_subdiguis. Adem�s, Digui asigna a ese
usuario un color de subrayado, que es diferente de los colores asignados a los
dem�s usuarios. Esta asignaci�n se almacena en la tabla 
mdl_digui_colors_assignments. El texto subrayado por este usuario, ser� pintado
con ese color. Dos usuarios no pueden tener el mismo color. Cuando dos o m�s
usuarios subrayan el mismo bloque de texto, el color resultante ser� gris. 
Cuanto m�s oscuro sea el gris, esto significa que m�s usuarios han subrayado el
mismo bloque de texto. Hay once colores disponibles, lo que significa que hasta
11 usuarios diferentes pueden editar el mismo digui. Los colores disponibles 
est�n almacenados en la tabla mdl_digui_colors. Pr�ximas versiones de Digui, 
podr�an a�adir soporte para m�s usuarios.

Cada vez que un usuario subraya un bloque de texto, una nueva entrada es 
a�adida en la tabla mdl_digui_spans. Esta tabla almacena las marcas que cad
usuario ha hecho en cada p�gina. Las tablas mdl_digui_spans y 
mdl_digui_page_version est�n relacionadas mediante el campo com�n 
"pageversion". Cuando el usuario subraya una secci�n de texto la primera vez,
una nueva marca se a�ade a la tabla mdl_digui_spans, y una nueva entrada se
a�ade a la tabla mdl_digui_page_version, con el campo "pageversion" 
inicializado a 1. Despu�s de esto, cuando el usuario subraya de nuevo, una 
nueva marca se a�ade a la tabla mdl_digui_spans, y se incrementa en 1 el campo 
"pageversion" de la tabla mdl_digui_page_version.Cuando el usuario presiona el
bot�n Deshacer, el campo "pageversion" se decrementa en 1, en la tabla
mdl_digui_page_version. Cuando el usuario presiona el bot�n Rehacer, el campo 
"pageversion" se incrementa en 1, en la tabla mdl_digui_page_version. S�lo se
visualizan las marcas de subrayado cuyo campo pageversion (en la tabla 
mdl_digui_spans), sea igual al campo pageversion de la tabla 
mdl_digui_page_version.

Aunque una caja de texto aparece en la ficha Editar, la �ltima versi�n de Digui
no admite anotaciones de p�gina. En pr�ximas versiones de Digui, esta 
funcionalidad podr�a ser activada.

La ficha Evaluar es �til para evaluar a los estudiantes. Adem�s, esta ficha 
muestra informaci�n sobre los usuarios que est�n editando el Digui, sobre los
estudiantes pendientes de ser calificados, y las notas de cada uno, que pueden
ser modificadas por un usuario con el rol de profesor. Para contar los usuarios
que necesitan ser calificados, Digui utiliza la tabla 
mdl_digui_last_user_modification.


T�CNICAS DE SUBRAYADO
=====================

a. Subrayar bloques de testo

   Cuando el puntero de rat�n adopte la apariencia de un marcador, haga lo
   siguiente:

   1. Haga clic con el bot�n izquierdo de su rat�n, al comienzo del bloque de 
   texto que desea subrayar. 
   2. Mantenga pulsada la tecla Mayus de su teclado (las teclas Mayus tienen
   dibujado una flecha sobre ellas, apuntando hacia arriba).
   3. Con la tecla Mayus presionada, haga clic click con el bot�n izquierdo de 
   su rat�n al final del bloque de texto que desea subrayar.
   4. Finalmente, el bloque de texto queda subrayado.

b. Subrayado mediante arrastre

   Cuando el puntero de rat�n adopte la apariencia de un marcador, haga lo
   siguiente:
 
   1. Haga clic con el bot�n izquierdo de su rat�n, al comienzo del bloque de 
   texto que desea subrayar. 
   2. Mantenga pulsado el bot�n izquierdo de su rat�n.
   3. Mueva el puntero de su rat�n por la pantalla.
   4. Cuando haya llegado al final del bloque de texto que desea subrayar, 
   deje de pulsar el bot�n izquierdo de su rat�n.
   5. Finalmente, el bloque de texto queda subrayado.

c. Subrayar una palabra

   Cuando el puntero de rat�n adopte la apariencia de un marcador, haga lo
   siguiente:

   1. Haga doble clic con el bot�n izquierdo de su rat�n, sobre la palabra que
   desea subrayar.
   2. Finalmente, la palabra queda subrayada.

d. Quitar el subrayado del documento

   Presione el bot�n "Borrar todo" para quitar el subrayado de una p�gina de 
   texto del documento.


   
AGRADECIMIENTOS
===============

�Gracias por usar Digui!

Fernando Mart�n
Antonio Gabriel L�pez