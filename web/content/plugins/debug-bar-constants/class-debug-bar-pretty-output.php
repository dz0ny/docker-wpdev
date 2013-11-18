<?php
/**
 * Debug Bar Constants - Debug Bar Pretty Output
 *
 * @package WordPress\Plugins\Debug Bar Constants
 * @since 1.2
 * @version 1.2.1.1
 *
 * @author Juliette Reinders Folmer
 *
 * @copyright 2013 Juliette Reinders Folmer
 * @license http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2
 */

if ( !class_exists( 'Debug_Bar_Pretty_Output' ) && class_exists( 'Debug_Bar_Panel' ) ) {
	/**
	 * Class Debug_Bar_Pretty_Output
	 */
	class Debug_Bar_Pretty_Output {

		const CONTEXT = 'debug-bar-constants';


		/**
		 * A not-so-pretty method to show pretty output ;-)
		 *
		 * @param   mixed   $var        Variable to show
		 * @param   string  $title      (optional) Variable title
		 * @param   bool    $escape     (optional) Whether to character escape the textual output
		 * @param   string  $space      (internal) Indentation spacing
		 * @param   bool    $short      (internal) Short or normal annotation
		 * @param   string  $context    (internal) Output context
		 */
		public static function output( $var, $title = '', $escape = false, $space = '', $short = false, $context = self::CONTEXT ) {

			if ( $space === '' ) {
				print '<div class="pr_var">';
			}
			if ( !empty( $title ) ) {
				print '<h4 style="clear: both;">' . ( $escape === true ? esc_html( $title ) : $title ) . "</h4>\n";
			}

			if ( is_array( $var ) ) {
				print 'Array: <br />' . $space . '(<br />';
				if ( $short !== true ) {
					$spacing = $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				else {
					$spacing = $space . '&nbsp;&nbsp;';
				}
				foreach ( $var as $key => $value ) {
					print $spacing . '[' . ( $escape === true ? esc_html( $key ): $key );
					if ( $short !== true ) {
						print  ' ';
						switch ( true ) {
							case ( is_string( $key ) ) :
								print '<span style="color: #336600; background-color: transparent;"><b><i>(string)</i></b></span>';
								break;
							case ( is_int( $key ) ) :
								print '<span style="color: #FF0000; background-color: transparent;"><b><i>(int)</i></b></span>';
								break;
							case ( is_float( $key ) ) :
								print '<span style="color: #990033; background-color: transparent;"><b><i>(float)</i></b></span>';
								break;
							default:
								print '(unknown)';
								break;
						}
					}
					print '] => ';
					self::output( $value, '', $escape, $spacing, $short, $context );
				}
				print $space . ')<br />';
			}
			else if ( is_string( $var ) ) {
				print '<span style="color: #336600; background-color: transparent;">';
				if ( $short !== true ) {
					print '<b><i>string['
						. strlen( $var )
					. ']</i></b> : ';
				}
				print '&lsquo;'
					. ( $escape === true ? str_replace( '  ', ' &nbsp;', esc_html( $var ) ) : str_replace( '  ', ' &nbsp;', $var ) )
					. '&rsquo;</span><br />';
			}
			else if ( is_bool( $var ) ) {
				print '<span style="color: #000099; background-color: transparent;">';
				if ( $short !== true ) {
					print '<b><i>bool</i></b> : '
						. $var
						. ' ( = ';
				}
				else {
					print '<b><i>b</i></b> ';
				}
				print '<i>'
					. ( ( $var === false ) ? '<span style="color: #FF0000; background-color: transparent;">false</span>' : ( ( $var === true ) ? '<span style="color: #336600; background-color: transparent;">true</span>' : __( 'undetermined', $context ) ) );
				if ( $short !== true ) {
					print ' </i>)';
				}
				print '</span><br />';
			}
			else if ( is_int( $var ) ) {
				print '<span style="color: #FF0000; background-color: transparent;">';
				if ( $short !== true ) {
					print '<b><i>int</i></b> : ';
				}
				print ( ( $var === 0 ) ? '<b>' . $var . '</b>' : $var )
					. "</span><br />\n";
			}
			else if ( is_float( $var ) ) {
				print '<span style="color: #990033; background-color: transparent;">';
				if ( $short !== true ) {
					print '<b><i>float</i></b> : ';
				}
				print $var
					. '</span><br />';
			}
			else if ( is_null( $var ) ) {
				print '<span style="color: #666666; background-color: transparent;">';
				if ( $short !== true ) {
					print '<b><i>';
				}
				print 'null';
				if ( $short !== true ) {
					print '</i></b> : '
					. $var
					. ' ( = <i>NULL</i> )';
				}
				print '</span><br />';
			}
			else if ( is_resource( $var ) ) {
				print '<span style="color: #666666; background-color: transparent;">';
				if ( $short !== true ) {
					print '<b><i>resource</i></b> : ';
				}
				print $var;
				if ( $short !== true ) {
					print ' ( = <i>RESOURCE</i> )';
				}
				print '</span><br />';
			}
			else if ( is_object( $var ) ) {
				print 'object: <br />' . $space . '(<br />';
				if ( $short !== true ) {
					$spacing = $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				else {
					$spacing = $space . '&nbsp;&nbsp;';
				}
				self::object_info( $var, $escape, $spacing, $short, $context );
				print $space . ')<br /><br />';
			}
			else {
				print esc_html__( 'I haven\'t got a clue what this is: ', $context ) . gettype( $var ) . '<br />';
			}
			if ( $space === '' ) {
				print '</div>';
			}
		}


		/**
		 * Gather and print pretty output about objects
		 *
		 * @todo: get object properties to show the variable type on one line with the 'property'
		 * @todo: get scope of methods and properties
		 *
		 * @param   object  $obj        Object to show
		 * @param   bool    $escape     (internal) Whether to character escape the textual output
		 * @param   string  $space      (internal) Indentation spacing
		 * @param   bool    $short      (internal) Short or normal annotation
		 * @param   string  $context    (internal) Output context
		 */
		public static function object_info( $obj, $escape, $space, $short, $context = self::CONTEXT ) {

			print $space . '<b><i>Class</i></b>: ' . get_class( $obj ) . ' (<br />';
			if ( $short !== true ) {
				$spacing = $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			else {
				$spacing = $space . '&nbsp;&nbsp;';
			}
			$ov = get_object_vars( $obj );
			foreach ( $ov as $var => $val ) {
				if ( is_array( $val ) ) {
					print $spacing . '<b><i>property</i></b>: ' . $var . "<b><i> (array)</i></b>\n";
					self::output( $val, '' , $escape, $spacing, $short, $context );
				}
				else {
					print $spacing . '<b><i>property</i></b>: ' . $var . ' = ';
					self::output( $val, '' , $escape, $spacing, $short, $context );
				}
			}
			unset( $ov, $var, $val );
		
			$om = get_class_methods( $obj );
			foreach ( $om as $method ) {
				print $spacing . '<b><i>method</i></b>: ' . $method . "<br />\n";
			}
			unset( $om );
			print $space . ')<br /><br />';
		}


		/**
		 * Helper Function specific to the Debug bar plugin
		 * Outputs properties in a table and methods in an unordered list
		 *
		 * @param   object  $obj        Object for which to show the properties and methods
		 * @param   string  $context    (internal) Output context
		 * @param   bool    $is_sub     (internal) Toplevel or nested object
		 */
		public static function ooutput( $obj, $context = self::CONTEXT, $is_sub = false ) {

			$properties = get_object_vars( $obj );
			$methods    = get_class_methods( $obj );

			if ( $is_sub === false ) {
				echo '
		<h2><span>' . esc_html__( 'Properties:', $context ) . '</span>' . count( $properties ) . '</h2>
		<h2><span>' . esc_html__( 'Methods:', $context ) . '</span>' . count( $methods ) . '</h2>';
			}

			// Properties
			if ( is_array( $properties ) && !empty( $properties ) ) {
				$h = ( $is_sub === false ? 'h3' : 'h4' );
				echo '
		<' . $h . '>' . esc_html__( 'Object Properties:', $context ) . '</' . $h . '>';

				uksort( $properties, 'strnatcasecmp' );
				self::render_table( $properties, __( 'Property', $context ), __( 'Value', $context ), $context, $context );
			}

			// Methods
			if ( is_array( $methods ) && !empty( $methods ) ) {
				echo '
		<h3>' . esc_html__( 'Object Methods:', $context ) . '</h3>
		<ul class="' . $context . '">';

				uksort( $methods, 'strnatcasecmp' );

				foreach ( $methods as $method ) {
					echo '<li>' . $method . '()</li>';
				}
				unset( $method );
				echo '</ul>';
			}
		}


		/**
		 * Render the table output
		 *
		 * @param   array           $array  Array to be shown in the table
		 * @param   string          $col1   Label for the first table column
		 * @param   string          $col2   Label for the second table column
		 * @param   string|array    $class  One or more CSS classes to add to the table
		 * @param   string          $context
		 */
		public static function render_table( $array, $col1, $col2, $class = null, $context = self::CONTEXT ) {

			$classes = 'debug-bar-table';
			if ( isset( $class ) ) {
				if ( is_string( $class ) && $class !== '' ) {
					$classes .= ' ' . $class;
				}
				else if ( is_array( $class ) && !empty( $class ) ) {
					$classes = $classes . ' ' . implode( ' ', $class );
				}
			}
			$col1 = ( is_string( $col1 ) ? $col1 : __( 'Key', $context ) );
			$col2 = ( is_string( $col2 ) ? $col2 : __( 'Value', $context ) );

			self::render_table_start( $col1, $col2, $classes );
			self::render_table_rows( $array, $context );
			self::render_table_end();
		}


		/**
		 * Generate the table header
		 *
		 * @param   string          $col1   Label for the first table column
		 * @param   string          $col2   Label for the second table column
		 * @param   string|array    $class  One or more CSS classes to add to the table
		 */
		public static function render_table_start( $col1, $col2, $class = null ) {

			echo '
		<table class="' . $class . '">
			<thead>
			<tr>
				<th>' . esc_html( $col1 ) . '</th>
				<th>' . esc_html( $col2 ) . '</th>
			</tr>
			</thead>
			<tbody>';
		}


		/**
		 * Generate table rows
		 * @param   array           $array  Array to be shown in the table
		 * @param   string          $context
		 */
		public static function render_table_rows( $array, $context = self::CONTEXT ) {

			foreach ( $array as $key => $value ) {
				self::render_table_row( $key, $value, $context );
			}
		}


		/**
		 * Generate individual table row
		 * @param   mixed   $key    Item key to use a row label
		 * @param   mixed   $value  Value to show
		 * @param   string  $context
		 */
		public static function render_table_row( $key, $value, $context = self::CONTEXT ) {

			echo '
			<tr>
				<th>
					' . esc_html( $key ) . '
				</th>
				<td>';

			if ( is_object( $value ) ) {
				self::ooutput( $value, $context, true );
			}
			else {
				self::output( $value, '', true, '', false, $context );
			}

			echo '
				</td>
			</tr>';
		}


		/**
		 * Generate table closing
		 */
		public static function render_table_end() {

			echo '
			</tbody>
		</table>
';
		}
	} // End of class Debug_Bar_Pretty_Output
} // End of if class_exists wrapper




if ( !class_exists( 'Debug_Bar_List_PHP_Classes' ) ) {
	/**
	 * This class does nothing, just a way to keep the list of php classes out of the global namespace
	 * You can retrieve the list by using the static variable Debug_Bar_List_PHP_Classes::$PHP_classes
	 * List last updated: 2013-05-05
	 */
	class Debug_Bar_List_PHP_Classes {

		public static $PHP_classes = array(

			/* == "Core" == */
			'stdClass',
			'__PHP_Incomplete_Class',
			'php_user_filter',

			// Interfaces
			'Traversable',
			'Iterator',
			'IteratorAggregate',
			'ArrayAccess',
			'Serializable',
			'Closure',

			// Exceptions
			'Exception',
			'ErrorException',


			/* == Affecting PHPs Behaviour == */
			// APC
			'APCIterator',

			// Weakref
			'WeakRef',
			'WeakMap',


			/* == Audio Formats Manipulation == */
			// KTaglib
			'KTaglib_MPEG_File',
			'KTaglib_MPEG_AudioProperties',
			'KTaglib_Tag',
			'KTaglib_ID3v2_Tag',
			'KTaglib_ID3v2_Frame',
			'KTaglib_ID3v2_AttachedPictureFrame',


			/* == Authentication Services == */

			/* == Date and Time Related Extensions == */
			// Date/Time
			'DateTime',
			'DateTimeZone',
			'DateInterval',
			'DatePeriod',


			/* == Command Line Specific Extensions == */

			/* == Compression and Archive Extensions == */
			// Phar
			'Phar',
			'PharData',
			'PharFileInfo',
			'PharException',

			// Rar
			'RarArchive',
			'RarEntry',
			'RarException',

			// Zip
			'ZipArchive',


			/* == Credit Card Processing == */

			/* == Cryptography Extensions == */

			/* == Database Extensions == */

				/* = Abstraction Layers = */
				// PDO
				'PDO',
				'PDOStatement',
				'PDOException',
				'PDORow',  // Not in PHP docs


				/* = Vendor Specific Database Extensions = */
				// Mongo
					// Mongo Core Classes
					'MongoClient',
					'MongoDB',
					'MongoCollection',
					'MongoCursor',

					// Mongo Types
					'MongoId',
					'MongoCode',
					'MongoDate',
					'MongoRegex',
					'MongoBinData',
					'MongoInt32',
					'MongoInt64',
					'MongoDBRef',
					'MongoMinKey',
					'MongoMaxKey',
					'MongoTimestamp',

					// Mongo GridFS Classes
					'MongoGridFS',
					'MongoGridFSFile',
					'MongoGridFSCursor',

					// Mongo Miscellaneous
					'MongoLog',
					'MongoPool',
					'Mongo',

					// Mongo Exceptions
					'MongoException',
					'MongoResultException',
					'MongoCursorException',
					'MongoCursorTimeoutException',
					'MongoConnectionException',
					'MongoGridFSException',


				// MySQL
					// Mysqli - MySQL Improved Extension
					'mysqli',
					'mysqli_stmt',
					'mysqli_result',
					'mysqli_driver',
					'mysqli_warning',
					'mysqli_sql_exception',

					// mysqlnd_uh - Mysqlnd user handler plugin
					'MysqlndUhConnection',
					'MysqlndUhPreparedStatement',

				// OCI8 - Oracle OCI8
				'OCI-Collection',
				'OCI-Lob',

				// SQLLite
				'SQLiteDatabase',  // Not easy to find in PHP docs
				'SQLiteResult',  // Not easy to find  in PHP docs
				'SQLiteUnbuffered',  // Not easy to find  in PHP docs
				'SQLiteException',	// Not easy to find  in PHP docs

				// SQLite3
				'SQLite3',
				'SQLite3Stmt',
				'SQLite3Result',

				// tokyo_tyrant
				'TokyoTyrant',
				'TokyoTyrantTable',
				'TokyoTyrantQuery',
				'TokyoTyrantIterator',
				'TokyoTyrantException',


			/* == File System Related Extensions == */
			// Directories
			'Directory',


			/* == Human Language and Character Encoding Support == */
			// Gender
			'Gender\Gender',

			// intl
			'Collator',
			'NumberFormatter',
			'Locale',
			'Normalizer',
			'MessageFormatter',
			'IntlDateFormatter',
			'ResourceBundle',
			'Spoofchecker',
			'Transliterator',


			/* == Image Processing and Generation == */
			// Cairo
			'Cairo',
			'CairoContext',
			'CairoException',
			'CairoStatus',
			'CairoSurface',
			'CairoSvgSurface',
			'CairoImageSurface',
			'CairoPdfSurface',
			'CairoPsSurface',
			'CairoSurfaceType',
			'CairoFontFace',
			'CairoFontOptions',
			'CairoFontSlant',
			'CairoFontType',
			'CairoFontWeight',
			'CairoScaledFont',
			'CairoToyFontFace',
			'CairoPatternType',
			'CairoPattern',
			'CairoGradientPattern',
			'CairoSolidPattern',
			'CairoSurfacePattern',
			'CairoLinearGradient',
			'CairoRadialGradient',
			'CairoAntialias',
			'CairoContent',
			'CairoExtend',
			'CairoFormat',
			'CairoFillRule',
			'CairoFilter',
			'CairoHintMetrics',
			'CairoHintStyle',
			'CairoLineCap',
			'CairoLineJoin',
			'CairoMatrix',
			'CairoOperator',
			'CairoPath',
			'CairoPsLevel',
			'CairoSubpixelOrder',
			'CairoSvgVersion',

			// Gmagick
			'Gmagick',
			'GmagickDraw',
			'GmagickPixel',

			// ImageMagick
			'Imagick',
			'ImagickDraw',
			'ImagickPixel',
			'ImagickPixelIterator',


			/* == Mail Related Extensions == */

			/* == Mathematical Extensions == */
			// Lapack
			'Lapack',
			'LapackException',


			/* == Non-Text MIME Output == */
			// haru
			'HaruException',
			'HaruDoc',
			'HaruPage',
			'HaruFont',
			'HaruImage',
			'HaruEncoder',
			'HaruOutline',
			'HaruAnnotation',
			'HaruDestination',

			// Ming
			'SWFAction',
			'SWFBitmap',
			'SWFButton',
			'SWFDisplayItem',
			'SWFFill',
			'SWFFont',
			'SWFFontChar',
			'SWFGradient',
			'SWFMorph',
			'SWFMovie',
			'SWFPrebuiltClip',
			'SWFShape',
			'SWFSound',
			'SWFSoundInstance',
			'SWFSprite',
			'SWFText',
			'SWFTextField',
			'SWFVideoStream',


			/* == Process Control Extensions == */
			// Ev
			'Ev',
			'EvCheck',
			'EvChild',
			'EvEmbed',
			'EvFork',
			'EvIdle',
			'EvIo',
			'EvLoop',
			'EvPeriodic',
			'EvPrepare',
			'EvSignal',
			'EvStat',
			'EvTimer',
			'EvWatcher',

			// pthreads
			'Thread',
			'Worker',
			'Stackable',
			'Mutex',
			'Cond',


			/* == Other Basic Extensions == */
			// JSON - JavaScript Object Notation
			'JsonSerializable',

			// Judy - Judy Arrays
			'Judy',

			// Lua
			'Lua',
			'LuaClosure',

			// SPL - Standard PHP Library (SPL)

				// SPL Data structures
				'SplDoublyLinkedList',
				'SplStack',
				'SplQueue',
				'SplHeap',
				'SplMaxHeap',
				'SplMinHeap',
				'SplPriorityQueue',
				'SplFixedArray',
				'SplObjectStorage',

				// SPL Iterators
				'AppendIterator',
				'ArrayIterator',
				'CachingIterator',
				'CallbackFilterIterator',
				'DirectoryIterator',
				'EmptyIterator',
				'FilesystemIterator',
				'FilterIterator',
				'GlobIterator',
				'InfiniteIterator',
				'IteratorIterator',
				'LimitIterator',
				'MultipleIterator',
				'NoRewindIterator',
				'ParentIterator',
				'RecursiveArrayIterator',
				'RecursiveCachingIterator',
				'RecursiveCallbackFilterIterator',
				'RecursiveDirectoryIterator',
				'RecursiveFilterIterator',
				'RecursiveIteratorIterator',
				'RecursiveRegexIterator',
				'RecursiveTreeIterator',
				'RegexIterator',

				'CachingRecursiveIterator', // Not in PHP docs - deprecated


				// SPL Interfaces
				'Countable',
				'OuterIterator',
				'RecursiveIterator',
				'SeekableIterator',
				'SplObserver',
				'SplSubject',

				// SPL Exceptions
				'BadFunctionCallException',
				'BadMethodCallException',
				'DomainException',
				'InvalidArgumentException',
				'LengthException',
				'LogicException',
				'OutOfBoundsException',
				'OutOfRangeException',
				'OverflowException',
				'RangeException',
				'RuntimeException',
				'UnderflowException',
				'UnexpectedValueException',

				// SPL File Handling
				'SplFileInfo',
				'SplFileObject',
				'SplTempFileObject',

				// SPL Miscellaneous Classes and Interfaces
				'ArrayObject',
				'SplObserver',
				'SplSubject',

			// SPL Types - SPL Type Handling
			'SplType',
			'SplInt',
			'SplFloat',
			'SplEnum',
			'SplBool',
			'SplString',

			// Streams
			'php_user_filter',
			'streamWrapper',

			// Tidy
			'tidy',
			'tidyNode',

			// V8js - V8 Javascript Engine Integration
			'V8Js',
			'V8JsException',

			// Yaf
			'Yaf_Application',
			'Yaf_Bootstrap_Abstract',
			'Yaf_Dispatcher',
			'Yaf_Config_Abstract',
			'Yaf_Config_Ini',
			'Yaf_Config_Simple',
			'Yaf_Controller_Abstract',
			'Yaf_Action_Abstract',
			'Yaf_View_Interface',
			'Yaf_View_Simple',
			'Yaf_Loader',
			'Yaf_Plugin_Abstract',
			'Yaf_Registry',
			'Yaf_Request_Abstract',
			'Yaf_Request_Http',
			'Yaf_Request_Simple',
			'Yaf_Response_Abstract',
			'Yaf_Route_Interface',
			'Yaf_Route_Map',
			'Yaf_Route_Regex',
			'Yaf_Route_Rewrite',
			'Yaf_Router',
			'Yaf_Route_Simple',
			'Yaf_Route_Static',
			'Yaf_Route_Supervar',
			'Yaf_Session',
			'Yaf_Exception',
			'Yaf_Exception_TypeError',
			'Yaf_Exception_StartupError',
			'Yaf_Exception_DispatchFailed',
			'Yaf_Exception_RouterFailed',
			'Yaf_Exception_LoadFailed',
			'Yaf_Exception_LoadFailed_Module',
			'Yaf_Exception_LoadFailed_Controller',
			'Yaf_Exception_LoadFailed_Action',
			'Yaf_Exception_LoadFailed_View',


			/* == Other Services == */
			// AMQP
			'AMQPConnection',
			'AMQPChannel',
			'AMQPExchange',
			'AMQPQueue',
			'AMQPEnvelope',

			// chdb - Constant hash database
			'chdb',

			// Event
			'Event',
			'EventBase',
			'EventBuffer',
			'EventBufferEvent',
			'EventConfig',
			'EventDnsBase',
			'EventHttp',
			'EventHttpConnection',
			'EventHttpRequest',
			'EventListener',
			'EventSslContext',
			'EventUtil',

			// Gearman
			'GearmanClient',
			'GearmanJob',
			'GearmanTask',
			'GearmanWorker',
			'GearmanException',

			// HTTP
			'HttpDeflateStream',
			'HttpInflateStream',
			'HttpMessage',
			'HttpQueryString',
			'HttpRequest',
			'HttpRequestPool',
			'HttpResponse',

			// Hyperwave API
			'hw_api',
			'hw_api_attribute',
			'hw_api_content',
			'hw_api_error',
			'hw_api_object',
			'hw_api_reason',

			// Memcache
			'Memcache',

			// Memcached
			'Memcached',

			// RRD - RRDtool
			'RRDCreator',
			'RRDGraph',
			'RRDUpdater',

			// Simple Asynchronous Messaging
			'SAMConnection',
			'SAMMessage',

			// SNMP
			'SNMP',
			'SNMPException',

			// Stomp - Stomp Client
			'Stomp',
			'StompFrame',
			'StompException',

			// SVM - Support Vector Machine
			'SVM',
			'SVMModel',

			// Varnish
			'VarnishAdmin',
			'VarnishStat',
			'VarnishLog',


			/* == Search Engine Extensions == */
			// Solr - Apache Solr
			'SolrUtils',
			'SolrInputDocument',
			'SolrDocument',
			'SolrDocumentField',
			'SolrObject',
			'SolrClient',
			'SolrResponse',
			'SolrQueryResponse',
			'SolrUpdateResponse',
			'SolrPingResponse',
			'SolrGenericResponse',
			'SolrParams',
			'SolrModifiableParams',
			'SolrQuery',
			'SolrException',
			'SolrClientException',
			'SolrIllegalArgumentException',
			'SolrIllegalOperationException',

			// Sphinx - Sphinx Client
			'SphinxClient',

			// Swish Indexing
			'Swish',
			'SwishResult',
			'SwishResults',
			'SwishSearch',


			/* == Server Specific Extensions == */

			/* == Session Extensions == */
			// Sessions - Session Handling
			'SessionHandler',
			'SessionHandlerInterface',


			/* == Text Processing == */

			/* == Variable and Type Related Extensions == */
			// Quickhash
			'QuickHashIntSet',
			'QuickHashIntHash',
			'QuickHashStringIntHash',
			'QuickHashIntStringHash',

			// Reflection
			'Reflection',
			'ReflectionClass',
			'ReflectionZendExtension',
			'ReflectionExtension',
			'ReflectionFunction',
			'ReflectionFunctionAbstract',
			'ReflectionMethod',
			'ReflectionObject',
			'ReflectionParameter',
			'ReflectionProperty',
			'Reflector',
			'ReflectionException',


			/* == Web Services == */
			// OAuth
			'OAuth',
			'OAuthProvider',
			'OAuthException',

			// SCA
			'SCA',
			'SCA_LocalProxy',
			'SCA_SoapProxy',

			// SOAP
			'SoapClient',
			'SoapServer',
			'SoapFault',
			'SoapHeader',
			'SoapParam',
			'SoapVar',


			/* == Windows Only Extensions == */

			// COM - COM and .Net (Windows)
			'COM',
			'DOTNET',
			'VARIANT',
			'COMPersistHelper', // Not in PHP docs
			'com_exception', // Not in PHP docs
			'com_safearray_proxy', // Not in PHP docs


			/* == XML Manipulation == */
			// DOM - Document Object Model
			'DOMAttr',
			'DOMCdataSection',
			'DOMCharacterData',
			'DOMComment',
			'DOMDocument',
			'DOMDocumentFragment',
			'DOMDocumentType',
			'DOMElement',
			'DOMEntity',
			'DOMEntityReference',
			'DOMException',
			'DOMImplementation',
			'DOMNamedNodeMap',
			'DOMNode',
			'DOMNodeList',
			'DOMNotation',
			'DOMProcessingInstruction',
			'DOMText',
			'DOMXPath',

			'DOMCdataSection', // Not in PHP docs
			'DOMConfiguration', // Not in PHP docs
			'DOMDocumentType', // Not in PHP docs
			'DOMDomError', // Not in PHP docs
			'DOMErrorHandler', // Not in PHP docs
			'DOMImplementationList', // Not in PHP docs
			'DOMImplementationSource', // Not in PHP docs
			'DOMLocator', // Not in PHP docs
			'DOMNameList', // Not in PHP docs
			'DOMNameSpaceNode', // Not in PHP docs
			'DOMNotation', // Not in PHP docs
			'DOMStringExtend', // Not in PHP docs
			'DOMStringList', // Not in PHP docs
			'DOMTypeinfo', // Not in PHP docs
			'DOMUserDataHandler', // Not in PHP docs

			// libxml
			'libXMLError',

			// Service Data Objects
			'SDO_DAS_ChangeSummary',
			'SDO_DAS_DataFactory',
			'SDO_DAS_DataObject',
			'SDO_DAS_Setting',
			'SDO_DataFactory',
			'SDO_DataObject',
			'SDO_Exception',
			'SDO_List',
			'SDO_Model_Property',
			'SDO_Model_ReflectionDataObject',
			'SDO_Model_Type',
			'SDO_Sequence',

			// SDO Relational Data Access Service
			'SDO_DAS_Relational',

			// SDO XML Data Access Service
			'SDO_DAS_XML',
			'SDO_DAS_XML_Document',

			// SimpleXML
			'SimpleXMLElement',
			'SimpleXMLIterator',

			// XMLReader
			'XMLReader',

			// XMLWriter
			'XMLWriter',

			// XSL
			'XSLTProcessor',

		);
	} // End of class Debug_Bar_List_PHP_Classes
} // End of if class_exists wrapper