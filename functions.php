<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues the theme stylesheet on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'twentytwentyfive-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;


// ---Регистрируем новый тип записи "Городские События" ---

add_action( 'init', 'city_events_register_cpt' );

function city_events_register_cpt() {
    $labels = array(
        'name'               => 'Городские События',
        'singular_name'      => 'Городское Событие',
        'add_new'            => 'Добавить событие',
        'add_new_item'       => 'Добавить новое событие',
        'edit_item'          => 'Редактировать событие',
        'new_item'           => 'Новое событие',
        'view_item'          => 'Посмотреть на сайте', 
        'search_items'       => 'Найти событие',
        'menu_name'          => 'Городские События'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,  
        'show_ui'             => true,
        'show_in_menu'        => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'city-events'), 
        'supports'            => array( 'title', 'editor' ), 
        'show_in_rest'        => false, 
    );

    register_post_type( 'city_event', $args );
}


// --- REST API, Авторизация и Формат ошибок ---

// Задаем API-ключ 
define( 'CITY_EVENTS_API_KEY', 'my-secret-key-123' ); 

// Функция для проверки авторизации (будем подключать к каждому нашему роуту)
function city_events_check_auth( WP_REST_Request $request ) {

    $api_key = $request->get_header( 'x-api-key' ); 

    if ( $api_key !== CITY_EVENTS_API_KEY ) {
        return new WP_Error(
            'UNAUTHORIZED', 
            'API-ключ не передан или неверен', 
            array( 'status' => 401 )
        );
    }

    return true; 
}

// Форматируем все ошибки в нашем пространстве имен под требования ТЗ
add_filter( 'rest_pre_echo_response', 'city_events_format_rest_errors', 10, 3 );

function city_events_format_rest_errors( $result, $server, $request ) {
    
    if ( strpos( $request->get_route(), '/events/v1' ) === 0 ) {
        
        if ( is_array( $result ) && isset( $result['code'] ) && isset( $result['message'] ) && isset( $result['data']['status'] ) && $result['data']['status'] >= 400 ) {
            
            $formatted_error = array(
                'error' => array(
                    'message' => $result['message'],
                    'code'    => $result['code']
                )
            );
            
            return $formatted_error;
        }
    }
    return $result;
}

// Регистрация тестового эндпоинта, чтобы проверить, что фундамент работает
add_action( 'rest_api_init', function() {
    // Маршрут для списка (GET) и создания (POST)
    register_rest_route( 'events/v1', '/events', array(
        array(
            'methods'             => 'GET',
            'callback'            => 'city_events_get_list',
            'permission_callback' => 'city_events_check_auth',
        ),
        array(
            'methods'             => 'POST',
            'callback'            => 'city_events_create_event',
            'permission_callback' => 'city_events_check_auth',
        ),
    ));
// Маршрут для отдельного события (GET, PUT, DELETE)
    
    register_rest_route( 'events/v1', '/events/(?P<id>\d+)', array(
        array(
            'methods'             => 'GET',
            'callback'            => 'city_events_get_single',
            'permission_callback' => 'city_events_check_auth',
        ),
        array(
            'methods'             => 'PUT', 
            'callback'            => 'city_events_update_event',
            'permission_callback' => 'city_events_check_auth',
        ),
        array(
            'methods'             => 'DELETE',
            'callback'            => 'city_events_delete_event',
            'permission_callback' => 'city_events_check_auth',
        ),
    ));

    // Тестовый эндпоинт
    register_rest_route( 'events/v1', '/test', array(
        'methods'             => 'GET',
        'callback'            => function() { 
            return array('message' => 'API работает'); 
        },
        'permission_callback' => 'city_events_check_auth',
    ));
});
function city_events_test_endpoint( WP_REST_Request $request ) {
    return rest_ensure_response( array( 'message' => 'Авторизация успешна, API работает!' ) );
}

// ---Логика Popularity и Создание события ---

function city_events_calculate_popularity($start_at, $capacity, $tags_count) {
    $today = new DateTime('today');
    $start_date = new DateTime($start_at);
    
    $interval = $today->diff($start_date);
    $days_to_start = (int)$interval->format('%r%a');

    $raw = 3 + ($capacity / 1000) - ($days_to_start / 10) + $tags_count;

    if ($raw < 1) return 1;
    if ($raw > 5) return 5;
    
    return (int)round($raw);
}

//Обработчик создания события

function city_events_create_event( WP_REST_Request $request ) {
    $params = $request->get_json_params();

    // Валидация обязательных полей
    $required = array('title', 'place', 'start_at', 'end_at', 'capacity');
    foreach ($required as $field) {
        if ( empty($params[$field]) ) {
            return new WP_Error('INVALID_FIELDS', "Поле $field обязательно", array('status' => 422));
        }
    }

    // Валидация логики
    $start = strtotime($params['start_at']);
    $end   = strtotime($params['end_at']);
    if (!$start || !$end || $start >= $end) {
        return new WP_Error('INVALID_DATES', 'Дата начала должна быть раньше даты окончания', array('status' => 422));
    }

    $tags = isset($params['tags']) && is_array($params['tags']) ? $params['tags'] : array();
    if (count($tags) > 5) {
        return new WP_Error('TOO_MANY_TAGS', 'Максимум 5 тегов', array('status' => 422));
    }

    $capacity = (int)$params['capacity'];
    if ($capacity < 1 || $capacity > 5000) {
        return new WP_Error('INVALID_CAPACITY', 'Вместимость должна быть от 1 до 5000', array('status' => 422));
    }

    $status = isset($params['status']) ? $params['status'] : 'draft';
    if ($status === 'cancelled') {
        return new WP_Error('FORBIDDEN_STATUS', 'Нельзя создать событие со статусом cancelled', array('status' => 400));
    }

    // Расчет популярности
    $popularity = city_events_calculate_popularity($params['start_at'], $capacity, count($tags));
    if ($popularity === 1) {
        return new WP_Error('LOW_POPULARITY', 'Low popularity Not interesting Event', array('status' => 400));
    }

    // Создание записи в WP
    $post_id = wp_insert_post(array(
        'post_title'   => sanitize_text_field($params['title']),
        'post_type'    => 'city_event',
        'post_status'  => 'publish', 
    ));

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, 'place', sanitize_text_field($params['place']));
    update_post_meta($post_id, 'start_at', $params['start_at']);
    update_post_meta($post_id, 'end_at', $params['end_at']);
    update_post_meta($post_id, 'tags', json_encode($tags, JSON_UNESCAPED_UNICODE));
    update_post_meta($post_id, 'capacity', $capacity);
    update_post_meta($post_id, 'status', $status);
    update_post_meta($post_id, 'popularity', $popularity);
    update_post_meta($post_id, 'change_number', 1);

    $response_data = array(
        'id'            => $post_id,
        'link'          => get_permalink($post_id), 
        'api_url'       => rest_url("events/v1/events/$post_id"), 
        'title'         => $params['title'],
        'place'         => $params['place'],
        'start_at'      => $params['start_at'],
        'end_at'        => $params['end_at'],
        'tags'          => $tags,
        'capacity'      => $capacity,
        'status'        => $status,
        'popularity'    => $popularity,
        'change_number' => 1
    );

    $response = rest_ensure_response($response_data);
    $response->set_status(201);
    
    $response->header('Location', rest_url("events/v1/events/$post_id"));

    return $response;
}

// ---Улучшение админки (Meta Box) и валидация ---

// Панель с полями в админке
add_action('add_meta_boxes', function() {
    add_meta_box('city_event_details', 'Данные события', 'city_events_metabox_html', 'city_event', 'normal', 'high');
});

function city_events_metabox_html($post) {
    $place = get_post_meta($post->ID, 'place', true);
    $start_at = get_post_meta($post->ID, 'start_at', true);
    $end_at = get_post_meta($post->ID, 'end_at', true);
    $capacity = get_post_meta($post->ID, 'capacity', true);
    $status = get_post_meta($post->ID, 'status', true) ?: 'draft';
    $tags_raw = get_post_meta($post->ID, 'tags', true);
    $tags = $tags_raw ? implode(', ', json_decode($tags_raw, true)) : '';
    $popularity = get_post_meta($post->ID, 'popularity', true) ?: '—';
    $change_number = get_post_meta($post->ID, 'change_number', true) ?: '0';

    ?>
    <style>
        .ce-admin-link { background: #e7f3ff; padding: 12px; border-left: 4px solid #2271b1; margin-bottom: 20px; }
        .ce-required { border-left: 3px solid #d63638 !important; }
    </style>

    <div class="event-admin-fields">


        <p><strong>Место проведения *</strong><br />
        <input type="text" name="ce_place" value="<?php echo esc_attr($place); ?>" class="widefat ce-required"></p>
        
        <p><strong>Дата начала (ГГГГ-ММ-ДД ЧЧ:ММ) *</strong><br />
        <input type="text" name="ce_start_at" value="<?php echo esc_attr($start_at); ?>" 
               class="widefat ce-required ce-date-mask" placeholder="2026-05-10 10:00" maxlength="16"></p>
        
        <p><strong>Дата окончания (ГГГГ-ММ-ДД ЧЧ:ММ) *</strong><br />
        <input type="text" name="ce_end_at" value="<?php echo esc_attr($end_at); ?>" 
               class="widefat ce-required ce-date-mask" placeholder="2026-05-10 12:00" maxlength="16"></p>
        
        <p><strong>Вместимость (1-5000) *</strong><br />
        <input type="number" name="ce_capacity" value="<?php echo esc_attr($capacity); ?>" class="widefat ce-required" min="1" max="5000"></p>
        
        <p><strong>Теги (через запятую, макс 5)</strong><br />
        <input type="text" name="ce_tags" value="<?php echo esc_attr($tags); ?>" class="widefat" placeholder="кино, лекция, парк"></p>
        
        <p><strong>Статус события</strong><br />
        <select name="ce_status" class="widefat">
            <option value="draft" <?php selected($status, 'draft'); ?>>Draft (Черновик)</option>
            <option value="published" <?php selected($status, 'published'); ?>>Published (Опубликовано)</option>
            <option value="cancelled" <?php selected($status, 'cancelled'); ?>>Cancelled (Отменено)</option>
        </select></p>
        
        <div class="ce-stats">
            <p style="margin: 0 0 5px 0;"><strong>Статистика события:</strong></p>
            <p style="margin: 0;">Популярность (расчетная): <code><?php echo esc_html($popularity); ?></code></p>
            <p style="margin: 5px 0 0 0;">Номер изменения (change_number): <code><?php echo esc_html($change_number); ?></code></p>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Умная маска даты
        $('.ce-date-mask').on('input', function() {
            var val = $(this).val().replace(/\D/g, '');
            var res = '';
            if (val.length > 0) res += val.substr(0, 4);
            if (val.length > 4) res += '-' + val.substr(4, 2);
            if (val.length > 6) res += '-' + val.substr(6, 2);
            if (val.length > 8) res += ' ' + val.substr(8, 2);
            if (val.length > 10) res += ':' + val.substr(10, 2);
            $(this).val(res);
        });

        // Валидация перед отправкой формы
        $('#post').on('submit', function(e) {
            var isInvalid = false;
            $('.ce-required').each(function() {
                if (!$(this).val() || ($(this).hasClass('ce-date-mask') && $(this).val().length < 16)) {
                    $(this).css('background-color', '#fff8f8').css('border-color', '#d63638');
                    isInvalid = true;
                } else {
                    $(this).css('background-color', '#fff').css('border-color', '#ddd');
                }
            });
            
            if (isInvalid) {
                alert('Пожалуйста, заполните обязательные поля в корректном формате (ГГГГ-ММ-ДД ЧЧ:ММ)');
                $('#publish').removeClass('disabled');
                $('.spinner').removeClass('is-active');
                return false;
            }
        });
    });
    </script>
    <?php
}

// Сохранение данных из админки
add_action('save_post_city_event', 'city_events_save_admin_data', 10, 3);

function city_events_save_admin_data($post_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['ce_place'])) return; 

    $tags_input = isset($_POST['ce_tags']) ? explode(',', $_POST['ce_tags']) : array();
    $tags = array_slice(array_map('trim', array_filter($tags_input)), 0, 5);

    $capacity = (int)$_POST['ce_capacity'];
    $popularity = city_events_calculate_popularity($_POST['ce_start_at'], $capacity, count($tags));

    update_post_meta($post_id, 'place', sanitize_text_field($_POST['ce_place']));
    update_post_meta($post_id, 'start_at', sanitize_text_field($_POST['ce_start_at']));
    update_post_meta($post_id, 'end_at', sanitize_text_field($_POST['ce_end_at']));
    update_post_meta($post_id, 'capacity', $capacity);
    update_post_meta($post_id, 'status', sanitize_text_field($_POST['ce_status']));
    update_post_meta($post_id, 'tags', json_encode($tags,JSON_UNESCAPED_UNICODE));
    update_post_meta($post_id, 'popularity', $popularity);

    $current_change = (int)get_post_meta($post_id, 'change_number', true);
    update_post_meta($post_id, 'change_number', $current_change + 1);
}

// --- Список событий с курсором ---

add_filter( 'posts_where', 'city_events_cursor_where', 10, 2 );
function city_events_cursor_where( $where, $query ) {
    global $wpdb;
    
    $cursor = $query->get( 'city_cursor_data' );
    if ( empty( $cursor ) ) return $where;

    $sort = $query->get( 'city_sort_type' );
    $dir = isset($cursor['dir']) ? $cursor['dir'] : 'next'; // Направление: next или prev
    
    $last_id = (int)$cursor['id'];
    $last_pop = (int)$cursor['pop'];
    
    // Если идем назад (prev), меняем знаки сравнения на противоположные
    $comp_less = ($dir === 'next') ? '<' : '>';
    $comp_more = ($dir === 'next') ? '>' : '<';

    if ( $sort === 'popularity' ) {
        $where .= $wpdb->prepare("
            AND (
                (SELECT meta_value+0 FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'popularity') {$comp_less} %d
                OR (
                    (SELECT meta_value+0 FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'popularity') = %d
                    AND {$wpdb->posts}.ID {$comp_more} %d
                )
            )
        ", $last_pop, $last_pop, $last_id);
    } else {
        $last_start = $cursor['start'];
        $where .= $wpdb->prepare("
            AND (
                (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'start_at') {$comp_more} %s
                OR (
                    (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'start_at') = %s
                    AND (SELECT meta_value+0 FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'popularity') {$comp_less} %d
                )
                OR (
                    (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'start_at') = %s
                    AND (SELECT meta_value+0 FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'popularity') = %d
                    AND {$wpdb->posts}.ID {$comp_more} %d
                )
            )
        ", $last_start, $last_start, $last_pop, $last_start, $last_pop, $last_id);
    }
    return $where;
}

function city_events_get_list( WP_REST_Request $request ) {
    $params = $request->get_query_params();
    $limit = isset($params['limit']) ? min((int)$params['limit'], 50) : 10;

    $cursor_data = null;
    if ( !empty($params['cursor']) ) {
        $cursor_data = json_decode(base64_decode($params['cursor']), true);
    }

    $meta_query = array(
        'relation' => 'AND',
        'popularity_clause' => array('key' => 'popularity', 'type' => 'NUMERIC'),
        'start_at_clause'   => array('key' => 'start_at', 'type' => 'DATETIME'),
    );

    if (!empty($params['status'])) $meta_query[] = array('key' => 'status', 'value' => sanitize_text_field($params['status']));
    if (!empty($params['place']))  $meta_query[] = array('key' => 'place', 'value' => sanitize_text_field($params['place']));
    $min_p = isset($params['min_popularity']) ? (int)$params['min_popularity'] : 1;
    $max_p = isset($params['max_popularity']) ? (int)$params['max_popularity'] : 5;
    $meta_query[] = array('key' => 'popularity', 'value' => array($min_p, $max_p), 'compare' => 'BETWEEN', 'type' => 'NUMERIC');
    if (!empty($params['tag'])) $meta_query[] = array('key' => 'tags', 'value' => '"'.sanitize_text_field($params['tag']).'"', 'compare' => 'LIKE');

    $sort_type = $params['sort'] ?? 'soon_and_popular';
    $dir = $cursor_data['dir'] ?? 'next';

    // Если идем назад (prev), нам нужно инвертировать порядок сортировки в SQL, 
    // чтобы взять "ближайшие предыдущие"
    if ($sort_type === 'popularity') {
        $orderby = ($dir === 'next') 
            ? array('popularity_clause' => 'DESC', 'ID' => 'ASC') 
            : array('popularity_clause' => 'ASC', 'ID' => 'DESC');
    } else {
        $orderby = ($dir === 'next')
            ? array('start_at_clause' => 'ASC', 'popularity_clause' => 'DESC', 'ID' => 'ASC')
            : array('start_at_clause' => 'DESC', 'popularity_clause' => 'ASC', 'ID' => 'DESC');
    }

    $query = new WP_Query(array(
        'post_type'        => 'city_event',
        'post_status'      => 'publish',
        'posts_per_page'   => $limit + 1, 
        'meta_query'       => $meta_query,
        'orderby'          => $orderby,
        'no_found_rows'    => false, 
        'city_cursor_data' => $cursor_data,
        'city_sort_type'   => $sort_type
    ));

    $posts = $query->posts;
    
    // Проверка, есть ли данные для следующего шага
    $has_more = count($posts) > $limit;
    if ( $has_more ) {
        array_pop($posts); 
    }

    if ($dir === 'prev') {
        $posts = array_reverse($posts);
    }

    $events = array();
    foreach ( $posts as $post ) {
        $events[] = array(
            'id'         => $post->ID,
            'title'      => $post->post_title,
            'place'      => get_post_meta($post->ID, 'place', true),
            'start_at'   => get_post_meta($post->ID, 'start_at', true),
            'status'     => get_post_meta($post->ID, 'status', true),
            'popularity' => (int)get_post_meta($post->ID, 'popularity', true),
        );
    }

    // Формируем ссылки
    $links = array('self' => add_query_arg($params, rest_url('events/v1/events')), 'next' => null, 'prev' => null);

    if ( !empty($events) ) {
        // next: Показываем, только если база вернула больше, чем лимит
        // или если мы шли назад.
        if ( $has_more || $dir === 'prev' ) {
            $last = end($events);
            $next_cursor = base64_encode(json_encode([
                'id' => $last['id'], 'pop' => $last['popularity'], 'start' => $last['start_at'], 'dir' => 'next'
            ]));
            $links['next'] = add_query_arg(array_merge($params, ['cursor' => $next_cursor]), rest_url('events/v1/events'));
        }

        // prev: Показываем, только если мы не на первой странице (есть входящий курсор)
        // или если при движении назад.
        if ( !empty($cursor_data) ) {

             if ($dir === 'next' || ($dir === 'prev' && $has_more)) {
                $first = $events[0];
                $prev_cursor = base64_encode(json_encode([
                    'id' => $first['id'], 'pop' => $first['popularity'], 'start' => $first['start_at'], 'dir' => 'prev'
                ]));
                $links['prev'] = add_query_arg(array_merge($params, ['cursor' => $prev_cursor]), rest_url('events/v1/events'));
             }
        }
    }

    return rest_ensure_response(array(
        'data'  => $events,
        'meta'  => array(
            'total'    => (int)$query->found_posts,
            'per_page' => count($events)
        ),
        'links' => $links 
    ));
}

function city_events_get_single($request) {
    $id = $request['id'];
    
    if (get_post_type($id) !== 'city_event') {
        return new WP_Error('NOT_FOUND', 'Событие не найдено', array('status' => 404));
    }
    
    $response_data = array(
        'id'            => (int)$id,
        'title'         => get_the_title($id),
        'link'          => get_permalink($id),
        'place'         => get_post_meta($id, 'place', true),
        'start_at'      => get_post_meta($id, 'start_at', true),
        'end_at'        => get_post_meta($id, 'end_at', true),
        'tags'          => json_decode(get_post_meta($id, 'tags', true), true) ?: array(),
        'capacity'      => (int)get_post_meta($id, 'capacity', true),
        'status'        => get_post_meta($id, 'status', true),
        'popularity'    => (int)get_post_meta($id, 'popularity', true),
        'change_number' => (int)get_post_meta($id, 'change_number', true),
    );

    // Рекомендация по вторникам (2) или средам (3)
    $current_day = (int)date('N');
    if ( $current_day === 2 || $current_day === 3 ) {
        $response_data['recommendation'] = 'Рекомендуем по вторникам и средам';
    }
    
    return rest_ensure_response($response_data);
}


function city_events_update_event($request) {
    $id = $request['id'];
    $params = $request->get_json_params();
    
    
    if (get_post_type($id) !== 'city_event') {
        return new WP_Error('NOT_FOUND', 'Событие не найдено', array('status' => 404));
    }

    
    $current_status = get_post_meta($id, 'status', true);
    $db_start_at    = get_post_meta($id, 'start_at', true);
    $db_end_at      = get_post_meta($id, 'end_at', true);
    $db_capacity    = get_post_meta($id, 'capacity', true);
    $db_tags_raw    = get_post_meta($id, 'tags', true);
    $db_tags        = json_decode($db_tags_raw, true) ?: array();


    // Проверка даты начала (нельзя в прошлое)
    // Проверяем только если дата пришла в запросе и она отличается от текущей в БД
    if ( !empty($params['start_at']) && $params['start_at'] !== $db_start_at ) {
        if ( strtotime($params['start_at']) < time() ) {
            return new WP_Error('INVALID_DATE', 'Нельзя поменять дату начала на время в прошлом', array('status' => 400));
        }
    }

    // Проверка статуса (из cancelled нельзя в published)
    if ( isset($params['status']) && $params['status'] === 'published' ) {
        if ( $current_status === 'cancelled' ) {
            return new WP_Error('FORBIDDEN_STATUS_CHANGE', 'Нельзя вернуть отмененное событие в статус Published', array('status' => 400));
        }
    }

    $title    = isset($params['title'])    ? sanitize_text_field($params['title']) : get_the_title($id);
    $place    = isset($params['place'])    ? sanitize_text_field($params['place']) : get_post_meta($id, 'place', true);
    $start_at = isset($params['start_at']) ? sanitize_text_field($params['start_at']) : $db_start_at;
    $end_at   = isset($params['end_at'])   ? sanitize_text_field($params['end_at'])   : $db_end_at;
    $capacity = isset($params['capacity']) ? (int)$params['capacity'] : (int)$db_capacity;
    $tags     = isset($params['tags']) && is_array($params['tags']) ? $params['tags'] : $db_tags;
    $status   = isset($params['status'])   ? sanitize_text_field($params['status']) : $current_status;

    if ( strtotime($start_at) >= strtotime($end_at) ) {
        return new WP_Error('INVALID_DATES', 'Дата начала должна быть раньше даты окончания', array('status' => 422));
    }

    $popularity = city_events_calculate_popularity($start_at, $capacity, count($tags));
    
    if ($popularity === 1) {
        return new WP_Error('LOW_POPULARITY', 'Low popularity Not interesting Event', array('status' => 400));
    }


    if ( isset($params['title']) ) {
        wp_update_post(array('ID' => $id, 'post_title' => $title));
    }

    update_post_meta($id, 'place', $place);
    update_post_meta($id, 'start_at', $start_at);
    update_post_meta($id, 'end_at', $end_at);
    update_post_meta($id, 'capacity', $capacity);
    update_post_meta($id, 'tags', json_encode($tags, JSON_UNESCAPED_UNICODE));
    update_post_meta($id, 'status', $status);
    update_post_meta($id, 'popularity', $popularity);
    
    $current_change = (int)get_post_meta($id, 'change_number', true);
    update_post_meta($id, 'change_number', $current_change + 1);

    return city_events_get_single($request); 
}

function city_events_delete_event($request) {
    $id = $request['id'];
    
    if (get_post_type($id) !== 'city_event') {
        return new WP_Error('NOT_FOUND', 'Событие не найдено', array('status' => 404));
    }

    $status   = get_post_meta($id, 'status', true);
    $start_at = get_post_meta($id, 'start_at', true);
    
    $start_timestamp = strtotime($start_at);
    $now = time();

    /**
     * Событие не должно быть в статусе 'published'
     * Время начала должно быть в будущем (start_at > сейчас)
     */
    if ( $status === 'published' ) {
        return new WP_Error(
            'FORBIDDEN_DELETE', 
            'Нельзя удалить опубликованное событие. Сначала переведите его в черновик или отмените.', 
            array('status' => 403)
        );
    }

    if ( $start_timestamp <= $now ) {
        return new WP_Error(
            'FORBIDDEN_DELETE', 
            'Нельзя удалить событие, которое уже началось или прошло.', 
            array('status' => 403)
        );
    }
    
    wp_delete_post($id, true); 
    
    return rest_ensure_response(array('message' => 'Событие успешно удалено'));
}