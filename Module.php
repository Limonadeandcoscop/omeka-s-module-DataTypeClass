<?php

/*
 * Copyright Limonade&Co, 2018
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software.  You can use, modify and/ or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software's author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user's attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software's suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */

namespace DataTypeClass;

use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        // Switch all resources templates properties defined as "Specific class" to "Omeka resource"
        $entityManager = $serviceLocator->get('Omeka\EntityManager');
        $api = $serviceLocator->get('Omeka\ApiManager');
        $templates = $api->search('resource_templates', [], ['responseContent' => 'resource'])->getContent();
        foreach ($templates as $template) {
            $properties = $template->getResourceTemplateProperties();
            foreach ($properties as $p) {
                $p->setDataType('item:resource');
                $entityManager->flush();
            }
        }
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            'Omeka\Controller\Admin\Item',
            'view.add.after',
            [$this, 'renderAssets']
        );
        $sharedEventManager->attach(
            'Omeka\Controller\Admin\Item',
            'view.edit.after',
            [$this, 'renderAssets']
        );		
        $sharedEventManager->attach(
            'Omeka\DataType\Manager',
            'service.registered_names',
            [$this, 'addResourcesClassesServices']
        );
    }

    public function renderAssets(Event $event)
    {
        $view = $event->getTarget();
        $assetUrl = $view->plugin('assetUrl');
        $view->headScript()
            ->appendFile($assetUrl('js/data-type-class.js', 'DataTypeTypedResource'));
    }
	
    public function addResourcesClassesServices(Event $event)
    {
        $resourcesClasses = $this->getServiceLocator()->get('Omeka\ApiManager')->search('resource_classes')->getContent();

        $names = $event->getParam('registered_names');
        foreach ($resourcesClasses as $class) {
            $names[] = 'resource:item#'.$class->term();
        }
        $event->setParam('registered_names', $names);
    }
}
