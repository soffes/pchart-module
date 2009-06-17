<?php defined('SYSPATH') OR die('No direct access allowed.');
 /*
     pdata - Simplifying data population for pChart
     Copyright (C) 2008 Jean-Damien POGOLOTTI
     Version  1.13 last updated on 08/17/08

     http://pchart.sourceforge.net

     This program is free software: you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation, either version 1,2,3 of the License, or
     (at your option) any later version.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with this program.  If not, see <http://www.gnu.org/licenses/>.

     Class initialisation :
      pdata()
     data populating methods :
      importFromCSV($FileName,$Delimiter=',',$dataColumns=-1,$HasHeader=FALSE,$dataName=-1)
      addPoint($Value,$Serie='Serie1',$Description='')
     Series manipulation methods :
      addSerie($SerieName='Serie1')
      addAllSeries()
      removeSerie($SerieName='Serie1')
      setAbsciseLabelSerie($SerieName = 'Name')
      setSerieName($Name,$SerieName='Serie1')
  +   setSerieSymbol($Name,$Symbol)
      setXAxisName($Name='X Axis')
      setYAxisName($Name='Y Axis')
      setXAxisFormat($Format='number')
      setYAxisFormat($Format='number')
      setXAxisUnit($Unit='')
      setYAxisUnit($Unit='')
      removeSerieName($SerieName)
      removeAllSeries()
     data retrieval methods :
      data()
      dataDescription()
 */

 /* pdata class definition */
 class Pdata_Core
  {
   var $data;
   var $dataDescription;

   function __construct()
    {
     $this->data                           = '';
     $this->dataDescription                = '';
     $this->dataDescription['Position']    = 'Name';
     $this->dataDescription['Format']['X'] = 'number';
     $this->dataDescription['Format']['Y'] = 'number';
     $this->dataDescription['Unit']['X']   = NULL;
     $this->dataDescription['Unit']['Y']   = NULL;
    }

   function importFromCSV($FileName,$Delimiter=',',$dataColumns=-1,$HasHeader=FALSE,$dataName=-1)
    {
     $handle = @fopen($FileName,'r');
     if ($handle)
      {
       $HeaderParsed = FALSE;
       while (!feof($handle))
        {
         $buffer = fgets($handle, 4096);
         $buffer = str_replace(chr(10),'',$buffer);
         $buffer = str_replace(chr(13),'',$buffer);
         $Values = split($Delimiter,$buffer);

         if ( $buffer != '' )
          {
           if ( $HasHeader == TRUE && $HeaderParsed == FALSE )
            {
             if ( $dataColumns == -1 )
              {
               $ID = 1;
               foreach($Values as $key => $Value)
                { $this->setSerieName($Value,'Serie'.$ID); $ID++; }
              }
             else
              {
               $SerieName = '';

               foreach($dataColumns as $key => $Value)
                $this->setSerieName($Values[$Value],'Serie'.$Value);
              }
             $HeaderParsed = TRUE;
            }
           else
            {
             if ( $dataColumns == -1 )
              {
               $ID = 1;
               foreach($Values as $key => $Value)
                { $this->addPoint(intval($Value),'Serie'.$ID); $ID++; }
              }
             else
              {
               $SerieName = '';
               if ( $dataName != -1 )
                $SerieName = $Values[$dataName];

               foreach($dataColumns as $key => $Value)
                $this->addPoint($Values[$Value],'Serie'.$Value,$SerieName);
              }
            }
          }
        }
       fclose($handle);
      }
    }

   function addPoint($Value,$Serie='Serie1',$Description='')
    {
     if (is_array($Value) && count($Value) == 1)
      $Value = $Value[0];

     $ID = 0;
     for($i=0;$i<=count($this->data);$i++)
      { if(isset($this->data[$i][$Serie])) { $ID = $i+1; } }

     if ( count($Value) == 1 )
      {
       $this->data[$ID][$Serie] = $Value;
       if ( $Description != '' )
        $this->data[$ID]['Name'] = $Description;
       elseif (!isset($this->data[$ID]['Name']))
        $this->data[$ID]['Name'] = $ID;
      }
     else
      {
       foreach($Value as $key => $Val)
        {
         $this->data[$ID][$Serie] = $Val;
         if (!isset($this->data[$ID]['Name']))
          $this->data[$ID]['Name'] = $ID;
         $ID++;
        }
      }
    }

   function addSerie($SerieName='Serie1')
    {
     if ( !isset($this->dataDescription['Values']) )
      {
       $this->dataDescription['Values'][] = $SerieName;
      }
     else
      {
       $Found = FALSE;
       foreach($this->dataDescription['Values'] as $key => $Value )
        if ( $Value == $SerieName ) { $Found = TRUE; }

       if ( !$Found )
        $this->dataDescription['Values'][] = $SerieName;
      }
    }

   function addAllSeries()
    {
     unset($this->dataDescription['Values']);

     if ( isset($this->data[0]) )
      {
       foreach($this->data[0] as $Key => $Value)
        {
         if ( $Key != 'Name' )
          $this->dataDescription['Values'][] = $Key;
        }
      }
    }

   function removeSerie($SerieName='Serie1')
    {
     if ( !isset($this->dataDescription['Values']) )
      return(0);

     $Found = FALSE;
     foreach($this->dataDescription['Values'] as $key => $Value )
      {
       if ( $Value == $SerieName )
        unset($this->dataDescription['Values'][$key]);
      }
    }

   function setAbsciseLabelSerie($SerieName = 'Name')
    {
     $this->dataDescription['Position'] = $SerieName;
    }

   function setSerieName($Name,$SerieName='Serie1')
    {
     $this->dataDescription['Description'][$SerieName] = $Name;
    }

   function setXAxisName($Name='X Axis')
    {
     $this->dataDescription['Axis']['X'] = $Name;
    }

   function setYAxisName($Name='Y Axis')
    {
     $this->dataDescription['Axis']['Y'] = $Name;
    }

   function setXAxisFormat($Format='number')
    {
     $this->dataDescription['Format']['X'] = $Format;
    }

   function setYAxisFormat($Format='number')
    {
     $this->dataDescription['Format']['Y'] = $Format;
    }

   function setXAxisUnit($Unit='')
    {
     $this->dataDescription['Unit']['X'] = $Unit;
    }

   function setYAxisUnit($Unit='')
    {
     $this->dataDescription['Unit']['Y'] = $Unit;
    }

   function setSerieSymbol($Name,$Symbol)
    {
     $this->dataDescription['Symbol'][$Name] = $Symbol;
    }

   function removeSerieName($SerieName)
    {
     if ( isset($this->dataDescription['Description'][$SerieName]) )
      unset($this->dataDescription['Description'][$SerieName]);
    }

   function removeAllSeries()
    {
     foreach($this->dataDescription['Values'] as $Key => $Value)
      unset($this->dataDescription['Values'][$Key]);
    }

   function data()
    {
     return($this->data);
    }

   function dataDescription()
    {
     return($this->dataDescription);
    }
  }
