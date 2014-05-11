<?php
namespace Sandra\Services;

use Nette;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

/**
 * Description of EmailManager
 *
 * @author jurasm2
 */
class EmailManager extends BaseService
{

    /**
     *
     * @var IMailer
     */
    protected $mailer;

    /**
     *
     * @var \Nette\Application\IPresenter
     */
    protected $presenter;

    /**
     * Constructor
     * @param \Nette\Mail\IMailer $mailer
     */
    public function __construct(IMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     *
     * @param array $events
     * @return Message
     */
    protected function createEventMessage($events)
    {

        $template = new Nette\Templating\FileTemplate(__DIR__ . '/EmailManager/notice.latte');
        $template->registerFilter(new Nette\Latte\Engine);
        $template->registerHelperLoader('Nette\Templating\Helpers::loader');
        $template->events = $events;
        $template->_control = $this->presenter;


        $mail = new Message;
        $mail->setFrom('Franta <franta@example.com>')
            ->addTo('jurasm2@gmail.com')
            ->setSubject('Sandra')
            ->setHtmlBody($template);
        return $mail;
    }

    public function sendEventMessages(array $events = null)
    {

        if ($events) {
//            dump('Ready to send ' . count($events) . ' messages');
//            die();
            $message = $this->createEventMessage($events);

            $this->mailer->send($message);

//            echo $message->getHtmlBody();
//            die();

        }
    }

}
