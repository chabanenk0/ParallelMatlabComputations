function remove_jscripts(filename)
% удаляет джаваскрипты (с возможными вирусами) из хтмл-файла для отправки
% на сервер

if (nargin<1)
    filename='index.html';
end
filename1='indextmp.html';
filename2='indextmp2.html';

%убираю скрипты
begincuttext=uint8('<SCRIPT');
endcuttext=uint8('</SCRIPT>');
remove_somestring(filename, filename1,begincuttext,endcuttext);

%убираю комменты
begincuttext=uint8('<!--');
endcuttext=uint8('-->');
res1=remove_somestring(filename1, filename2,begincuttext,endcuttext);
if(res1)
    delete(filename); % если нету файла - возвратит 0, иначе - 1
    delete(filename1);
    movefile(filename2,filename);
end

function res=remove_somestring(filename, filename1,begincuttext,endcuttext);

fp=fopen(filename,'r');
if (fp~=-1)
fp1=fopen(filename1,'w');

mode=0;
% 0 - режим ищем открытие тега скрипта для удаления, 
% 1 - нашли подозрительный кусок 
% 2 - нашли весь фрагмент начала удаления, ищем начало тега закрытия
% 3 - нашли подозрительный кусок окончания
% 4 - нашли весь фрагмент закрытия... А это то же, что и 0...
pos=1;
data=[];
printdata=[];

while ~(feof(fp))
   ch=fread(fp,1,'uint8'); 
   if (mode==0)
       if (ch==begincuttext(pos))
           mode=1;
           pos=pos+1;
           data=ch;
       else
           fwrite(fp1,ch,'uint8');
       end
   else
       if (mode==1)
           data=[data,ch];
           if (ch==begincuttext(pos))
               pos=pos+1;
               if (pos>length(begincuttext))
                   mode=2;
                   pos=1;
               end
           else
               fwrite(fp1,data,'uint8');
               data=[];
               pos=1;
               mode=0;
           end
       else
           if (mode==2)
               if (ch==endcuttext(pos))
                   mode=3;
                   pos=pos+1;
                   %data=ch;
               end
           else
               if (mode==3)
                   if (ch==endcuttext(pos))
                       pos=pos+1;
                       if (pos>length(endcuttext))
                           mode=0;
                           pos=1;
                       end
                   else
                       mode=2;
                       pos=1;
                   end
               end
           end
       end
           
   end
end
fclose(fp);
fclose(fp1);
res=1;
else
    res=0;
end
