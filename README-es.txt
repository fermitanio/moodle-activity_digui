DIGUI PARA MOODLE
=================

Hemos escrito un nuevo módulo para Moodle 3.1. Hemos enviado los archivos 
necesarios a la dirección https://github.com/fermitanio/moodle-activity_digui. 
El archivo comprimido digui.zip contiene la estructura completa del módulo, e 
incluye archivos readme. Para utilizar Digui, el archivo digui.zip debe ser 
descomprimido en el directorio mod de Moodle.

Puede ver vídeos sobre Digui en la dirección 
https://www.youtube.com/channel/UCyxlh0kmnfS207vVWPURhdQ. 

Para cualquier cuestión sobre Digui, por favor escriba a la dirección de 
email, fermitanio@hotmail.com.

Gracias.


REQUISITOS DEL SISTEMA
======================

* Digui debe ser instalado en Moodle 2.4.11, o versiones superiores, junto con
  la base de datos MySQL. Digui no ha sido probado con la base de datos 
  PostgreSQL.
  
* Digui admite los siguientes navegadores: Chrome, Firefox, Opera y Safari.
  Digui no ha sido probado en los navegadores Explorer y Edge.

* Para funcionar, Digui necesita que JavaScript esté activado en el navegador.


INSTALACIÓN RÁPIDA
==================

   Aquí está la información básica sobre el proceso de instalación, que suele 
   llevar unos minutos:

   1º El módulo está en un archivo comprimido llamado "Digui.zip". Debemos 
descomprimir este archivo en la carpeta "moodle/mod/".

   2º Identificarnos en Moodle como administrador.

   3º Seguir las instrucciones.
   
   Para desinstalar el módulo, por favor sigue los siguientes pasos:

   1º Acceder a Moodle como administrador.
   
   2º En Moodle, pulsar sobre "Administración del sitio", luego en 
   "Extensiones", luego en "Módulos de actividad", luego en "Gestionar 
   actividades".
   
   3º Buscar el módulo en la lista, y presionar en "Desinstalar".
   

   
QUÉ HACE ESTA ACTIVIDAD
=======================

   Muchas veces, el profesor entrega a sus estudiantes páginas de texto, para 
   que las lean. Sin embargo, la mayoría de las veces los estudiantes no leerán
   el documento completo, sino una selección. Digui incluye un marcador digital
   que permite subrayar secciones de documentos. Con la actividad Digui, usted
   puede subrayar texto y guardar el resultado en un archivo. Use la 
   herramienta de marcado para encontrar y marcar texto importante en una 
   página web, como si se tratase de un papel. Usted puede enfatizar: 
   
    * una frase o palabra que contiene una idea importante,
    * citas,
    * estadísticas,
    * términos especiales,
    * datos útiles o importantes,
    * ejemplos o enlaces a otras ideas.
	   
   Utilizar marcadores para resaltar texto es una gran idea. Subrayar es 
   conveniente cuando los resúmenes son importantes para entender el
   material. Subrayar le ayuda a pensar críticamente y a formular sus propias
   respuestas al texto. Además, subrayar puede mejorar la retención del texto
   seleccionado, porque ayuda a concentrar la atención a hechos individuales. 
  
  
DESCRIPCIÓN DE DIGUI
====================

Digui es un programa para subrayar texto, hacer anotaciones y exportar los 
resultados a un archivo.


BENEFICIOS DE UTILIZAR DIGUI
============================

Digui es una herramienta para el aprendizaje y el trabajo colaborativo. Aunque
Digui es una actividad para Moodle, el profesor que lo utilice puede planificar
las actividades que deseé, de acuerdo con las características de su asignatura.
Así por ejemplo, con Digui el profesor podrá diseñar las siguientes 
actividades:

- Sus alumnos deberán responder a preguntas, subrayando las respuestas en un 
texto. Actividad válida para cualquier asignatura.
- Evaluar la comprensión lectora de los alumnos, subrayando palabras o frases
clave. Actividad válida para cualquier asignatura.
- Realizar resúmenes o síntesis de documentos. Actividad válida para cualquier 
asignatura.
- Señalar hipervínculos y otros elementos del hipertexto. Actividad válida para 
una asignatura de informática.
- Señalar sustantivos y otros elementos gramaticales. Actividad válida para 
una asignatura de lengua y literatura.
- Señalar tipos de datos, literales, y otros elementos de un lenguaje, para una
asignatura de programación informática.
- Señalar consultas, comandos, y otras órdenes y elementos de un lenguaje, para
una asignatura de bases de datos.
- Etc.


FUNCIONALIDADES BÁSICAS
=======================

- Más de un usuario pueden subrayar el mismo bloque de texto, mostrándose en 
color gris.
- Admite la importación de archivos epub, html y txt, y la exportación de 
resúmenes a archivos txt. 
- Admite hasta 11 usuarios por cada digui, es decir, 11 usuarios como máximo 
pueden editar el mismo digui.
- Si una página está siendo subrayada por un usuario, el siguiente usuario 
que quiera subrayar la misma página debe esperar a que el primer usuario 
abandone la página, o que el primer usuario esté inactivo durante 5 minutos.
- Admite los modos de Moodle individual o colaborativo.
- Admite los grupos de Moodle sin grupos, grupos visibles y grupos separados.


INTRODUCCIÓN A LA INTERFAZ
==========================

La interfaz de Digui está organizada en cuatro fichas: Ver, Editar, Evaluar y 
Exportar. Las fichas Ver y Editar muestran una página de texto, y el usuario
puede visualizar cualquier otra página de texto, usando los hipervínculos
inferiores. La ficha Evaluar es para uso exclusivo de usuarios con el rol de
profesor, y no será visualizada si el usuario activo tiene el rol de 
estudiante. Los profesores pueden usar la ficha Evaluar para poner una nota a
sus estudiantes. Finalmente, cualquier usuario puede guardar el texto subrayado
en un archivo de texto, mediante la ficha Exportar


MANUAL DEL USUARIO
==================

Antes de subrayar algo usted debe enviar a Moodle el archivo que contiene el 
texto a subrayar. Este archivo es único, y puede ser un archivo de texto plano 
(.txt) o una página web (.html). Después de esta etapa, Digui extrae el texto e
ignora las imágenes y otros elementos supérfluos del archivo. 

Desde este momento, usted puede seleccionar y subrayar texto. Para ello, debe 
abrir la solapa Editar y comenzar a arrastrar el ratón sobre la pantalla. 

Un mismo texto puede ser subrayado varias veces, por diferentes usuarios. En 
este caso el color del texto es gris, más oscuro cuantos más usuarios hayan 
subrayado ese texto.

Para ver los resultados debe abrir la solapa Ver. Si sus estudiantes están
separados en grupos, usted puede configurar Digui para reflejar esta 
organización. Si Digui está configurado en grupos separados, la solapa Ver
muestra sólo el resultado de los usuarios que estén en el grupo del usurio 
actual. Si Digui está configurado en grupos visibles, la solapa Ver muestra
el resultado de los grupos que usted elija.

Para guardar el texto subrayado en un archivo, debe utilizar la solapa 
Exportar. 

Sólo los profesores pueden evaluar al resto de usuarios, mediante la solapa 
Calificaciones. Sin embargo, después de que un profesor haya evaluado a todos
los usuarios, los estudiantes podrán ver sus calificaciones mediante la solapa
Calificaciones.


CÓMO FUNCIONA DIGUI
===================

El primer paso para usar Digui, es identificarse en Moodle como administrador, 
y añadir una actividad Digui. Los campos "Nombre del Digui" y "Título del 
libro" son obligatorios. Adem´s, el usuario puede establecer el modo del digui
como individual o como colaborativo. Si el modo es individual, cada págin del 
digui podrá ser subrayada por un único usuario. Si el modo es colaborativo, 
cada página del digui podrá ser subrayada por varios usuarios.

Después de que el usuario cree la actividad Digui, el segundo paso es enviar el
texto a subrayar. La fuente del texto puede ser un archivo epub, un archivo 
html o un archivo txt. El usuario puede enviar sólo un archivo. Durante el 
proceso de envío, Digui convierte el archivo fuente en texto con formato (si
el formato del archivo es epub o html), y divide el archivo en varias páginas
de texto, de unos 4000 caracteres aproximadamente cada página. Imágenes y otros
elementos son descartados. Las páginas son guardadas en el campo 
"cachedcontent" de la tabla mdl_digui_pages. Este campo no puede ser 
modificado, es de sólo lectura.

Después de enviar el archivo, el usuario puede subrayar el texto usando la 
ficha Editar. El usuario puede subrayar bloques de texto, arrastrando el ratón
sobre el texto. Si el modo del digui es individual, la ficha Editar muestra 
las marcas del usuario actual, únicamente. Si el modo del digui es 
colaborativo, la ficha Editar muestra las marcas de los usuarios que han 
subrayado la página actual.

Cuando un usuario hace click sobre una actividad de Digui por primera vez,
este usuario se añade a la tabla digui_subdiguis. Además, Digui asigna a ese
usuario un color de subrayado, que es diferente de los colores asignados a los
demás usuarios. Esta asignación se almacena en la tabla 
mdl_digui_colors_assignments. El texto subrayado por este usuario, será pintado
con ese color. Dos usuarios no pueden tener el mismo color. Cuando dos o más
usuarios subrayan el mismo bloque de texto, el color resultante será gris. 
Cuanto más oscuro sea el gris, esto significa que más usuarios han subrayado el
mismo bloque de texto. Hay once colores disponibles, lo que significa que hasta
11 usuarios diferentes pueden editar el mismo digui. Los colores disponibles 
están almacenados en la tabla mdl_digui_colors. Próximas versiones de Digui, 
podrían añadir soporte para más usuarios.

Cada vez que un usuario subraya un bloque de texto, una nueva entrada es 
añadida en la tabla mdl_digui_spans. Esta tabla almacena las marcas que cad
usuario ha hecho en cada página. Las tablas mdl_digui_spans y 
mdl_digui_page_version están relacionadas mediante el campo común 
"pageversion". Cuando el usuario subraya una sección de texto la primera vez,
una nueva marca se añade a la tabla mdl_digui_spans, y una nueva entrada se
añade a la tabla mdl_digui_page_version, con el campo "pageversion" 
inicializado a 1. Después de esto, cuando el usuario subraya de nuevo, una 
nueva marca se añade a la tabla mdl_digui_spans, y se incrementa en 1 el campo 
"pageversion" de la tabla mdl_digui_page_version.Cuando el usuario presiona el
botón Deshacer, el campo "pageversion" se decrementa en 1, en la tabla
mdl_digui_page_version. Cuando el usuario presiona el botón Rehacer, el campo 
"pageversion" se incrementa en 1, en la tabla mdl_digui_page_version. Sólo se
visualizan las marcas de subrayado cuyo campo pageversion (en la tabla 
mdl_digui_spans), sea igual al campo pageversion de la tabla 
mdl_digui_page_version.

Aunque una caja de texto aparece en la ficha Editar, la última versión de Digui
no admite anotaciones de página. En próximas versiones de Digui, esta 
funcionalidad podría ser activada.

La ficha Evaluar es útil para evaluar a los estudiantes. Además, esta ficha 
muestra información sobre los usuarios que están editando el Digui, sobre los
estudiantes pendientes de ser calificados, y las notas de cada uno, que pueden
ser modificadas por un usuario con el rol de profesor. Para contar los usuarios
que necesitan ser calificados, Digui utiliza la tabla 
mdl_digui_last_user_modification.


TÉCNICAS DE SUBRAYADO
=====================

a. Subrayar bloques de testo

   Cuando el puntero de ratón adopte la apariencia de un marcador, haga lo
   siguiente:

   1. Haga clic con el botón izquierdo de su ratón, al comienzo del bloque de 
   texto que desea subrayar. 
   2. Mantenga pulsada la tecla Mayus de su teclado (las teclas Mayus tienen
   dibujado una flecha sobre ellas, apuntando hacia arriba).
   3. Con la tecla Mayus presionada, haga clic click con el botón izquierdo de 
   su ratón al final del bloque de texto que desea subrayar.
   4. Finalmente, el bloque de texto queda subrayado.

b. Subrayado mediante arrastre

   Cuando el puntero de ratón adopte la apariencia de un marcador, haga lo
   siguiente:
 
   1. Haga clic con el botón izquierdo de su ratón, al comienzo del bloque de 
   texto que desea subrayar. 
   2. Mantenga pulsado el botón izquierdo de su ratón.
   3. Mueva el puntero de su ratón por la pantalla.
   4. Cuando haya llegado al final del bloque de texto que desea subrayar, 
   deje de pulsar el botón izquierdo de su ratón.
   5. Finalmente, el bloque de texto queda subrayado.

c. Subrayar una palabra

   Cuando el puntero de ratón adopte la apariencia de un marcador, haga lo
   siguiente:

   1. Haga doble clic con el botón izquierdo de su ratón, sobre la palabra que
   desea subrayar.
   2. Finalmente, la palabra queda subrayada.

d. Quitar el subrayado del documento

   Presione el botón "Borrar todo" para quitar el subrayado de una página de 
   texto del documento.


   
AGRADECIMIENTOS
===============

¡Gracias por usar Digui!

Fernando Martín
Antonio Gabriel López