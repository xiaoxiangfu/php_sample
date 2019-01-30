<?php
namespace Concrete\Core\Calendar\Event\Formatter;

use Concrete\Core\Config\Repository\Repository;
use HtmlObject\Link;
use Concrete\Core\Entity\Calendar\CalendarEvent;
use Concrete\Core\Entity\Calendar\CalendarEventVersionOccurrence;

class LinkFormatter implements LinkFormatterInterface
{
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function getEventOccurrenceBackgroundColor(CalendarEventVersionOccurrence $occurrence)
    {
        $categories = $this->config->get('concrete.calendar.colors.categories');
        if (is_array($categories) && count($categories)) {
            $topics = $occurrence->getEvent()->getCategories();
            if ($topics) {
                foreach ($topics as $topic) {
                    if (isset($categories[$topic->getTreeNodeName()])) {
                        $background = $categories[$topic->getTreeNodeName()]['background'];
                    }
                }
            }
        }
        if (!isset($background)) {
            $background = $this->config->get('concrete.calendar.colors.background');
        }

        return $background;
    }

    public function getEventOccurrenceTextColor(CalendarEventVersionOccurrence $occurrence)
    {
        $categories = $this->config->get('concrete.calendar.colors.categories');
        if (is_array($categories) && count($categories)) {
            $topics = $occurrence->getEvent()->getCategories();
            if ($topics) {
                foreach ($topics as $topic) {
                    if (isset($categories[$topic->getTreeNodeName()])) {
                        $text = $categories[$topic->getTreeNodeName()]['text'];
                    }
                }
            }
        }
        if (!isset($text)) {
            $text = $this->config->get('concrete.calendar.colors.text');
        }

        return $text;
    }

    public function getEventOccurrenceLinkObject(CalendarEventVersionOccurrence $occurrence)
    {
        $value = $occurrence->getEvent()->getName();
        if (!$value) {
            $value = t('(No Title)');
        }
        $page = $occurrence->getEvent()->getPageObject();
        $href = 'javascript:void(0)';
        if (!$occurrence->getVersion()->isApproved()) {
            $value .= '<i class="fa fa-exclamation-circle"></i>';
        }
        $background = $this->getEventOccurrenceBackgroundColor($occurrence);
        $text = $this->getEventOccurrenceTextColor($occurrence);

        $link = new Link($href, $value);
        $link->setAttribute('style', sprintf('background-color: %s; color: %s', $background, $text));

        if ($occurrence->isCancelled()) {
            $link->addClass('ccm-calendar-date-event-cancelled');
        }
        if ($occurrence->getEvent()->isPending()) {
            $link->addClass('ccm-calendar-date-event-pending');
        }

        return $link;
    }

    public function getEventOccurrenceFrontendViewLink(CalendarEventVersionOccurrence $occurrence)
    {
        $url = $this->getEventFrontendViewLink($occurrence->getEvent());
        if ($url) {
            $url .= '?occurrenceID=' . $occurrence->getId();
        }
        return $url;
    }

    public function getEventFrontendViewLink(CalendarEvent $event)
    {
        $page = $event->getPageObject();
        $url = null;
        if ($page) {
            $url = $page->getCollectionLink();
        }
        return $url;

    }


}
