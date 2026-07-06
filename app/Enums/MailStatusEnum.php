<?php

namespace App\Enums;

enum MailStatusEnum : int
{
       
      case PUBLISHED = 1;
      case PENDING = 2;
      case NOTPUBLISHED = 3;      

}
  