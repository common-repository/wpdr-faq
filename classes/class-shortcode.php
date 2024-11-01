<?php

class FAQShortcode
{

    private $HTML;
    private $FAQ;

    public function __construct()
    {
        $this->HTML = new HTMLBuilder();
        $this->FAQ = new FAQ;
    }

    public function wp_faq_shortcode( $atts ){
        $terms = $this->FAQ->get_terms();
        // div.faq-container
        $this->HTML->create_element(array('element' => 'div', 'attributes' => array( 'id' => 'faq-container', 'class' => $this->FAQ->get_default_skin() )));
        if(is_array($terms) && !empty($terms)):
            //div#faq-nav
            $this->HTML->add_child('div', array('class' => 'faq-nav'));
                //a.all
                $this->HTML->add_child('a', array('href' => '#all', 'class' => 'all current'), 'All'); $this->HTML->close_child('a');
                foreach($terms as $k => $term):
                    ($k == count($terms)-1) ? $class = ' last' : $class = '';
                    // a.term_slug
                    $this->HTML->add_child('a', array('href' => '#' . $term->slug, 'class' => $term->slug . $class), $term->name); $this->HTML->close_child('a');
                endforeach;
                // div.line
                $this->HTML->add_child('div', array('class' => 'line')); $this->HTML->close_child('div');
            // close div.#faq-nav
            $this->HTML->close_child('div');
            // div.faq-questions
            $this->HTML->add_child('div', array('class' => 'faq-questions'));
            foreach($terms as $k => $term):
                // div#topic->slug
                $this->HTML->add_child('div', array('id' => 'faq-topics-' . $term->slug, 'class' => 'topics'));
                    // h3
                    $this->HTML->add_child('h3', array(), $term->name); $this->HTML->close_child('h3');
                    $questions = $this->FAQ->get_questions($term->slug);
                    if(!empty($questions)):
                        foreach($questions as $question):
                            // div.question
                            $this->HTML->add_child('div', array('class' => 'question ' . $question['post_name'] . ' collapsed' . $class));
                                // div.toggle
                                $this->HTML->add_child('div', array( 'class' => 'toggle'));
                                    // div.left_c
                                    $this->HTML->add_child('div', array( 'class' => 'left_c')); $this->HTML->close_child('div');
                                    // div.right_c
                                    $this->HTML->add_child('div', array( 'class' => 'right_c')); $this->HTML->close_child('div');
                                    // div.center_c
                                    $this->HTML->add_child('div', array( 'class' => 'center_c'));
                                        // h4
                                        $this->HTML->add_child('h4', array(), $question['post_title']); $this->HTML->close_child('h4');
                                    $this->HTML->close_child('div');
                                // close div.toggle
                                $this->HTML->close_child('div');
                                // div.quest-content
                                $this->HTML->add_child('div', array('class' => 'quest-content'), '<p>' . $question['post_content'] . '</p><div class="clearer"></div>');
                                $this->HTML->close_child('div');
                            // close div.question
                            $this->HTML->close_child('div');
                        endforeach;

                    endif;
                // close div.topics
                $this->HTML->close_child('div');
            endforeach;
            // close div.faq_questions
            $this->HTML->close_child('div');
        endif;
        return $this->HTML->return_element();
    }

}

$FAQ_Shortcode = new FAQShortcode();
add_shortcode( 'wpfaq', array( $FAQ_Shortcode, 'wp_faq_shortcode' ));

?>