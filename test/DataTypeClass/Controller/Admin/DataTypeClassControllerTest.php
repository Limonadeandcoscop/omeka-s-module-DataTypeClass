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

namespace DataTypeClassTest\Controller;

use OmekaTestHelper\Controller\OmekaControllerTestCase;

class DataTypeClassTestControllerTest extends OmekaControllerTestCase
{
    public function setup()
    {
        parent::setup();
        $this->loginAsAdmin();
    }

    public function testSelectAction()
    {
        $this->dispatch('/admin/resource-template/1/edit');
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('admin/id');

        // Check that classes are added in "data type" combobox
        $this->assertXpathQuery('//select[@name="data_type"]/optgroup/option[contains(@value, "resource:item#dcterms:Agent")]');
    }

    protected function getPropertyId($term)
    {
        $response = $this->api()->search('properties', [
            'term' => $term,
        ]);
        $property = $response->getContent();

        if (!empty($property)) {
            return $property[0]->id();
        }
    }
}
