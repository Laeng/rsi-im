import {
    computePosition as floatingUi,
    offset as floatingUiOffset,
    flip as floatingUiFlip,
    shift as floatingUiShift,
    arrow as floatingUiArrow,
} from '@floating-ui/dom';
import type {Placement as FloatingUiPlacement} from '@floating-ui/dom';

export type TriggerType = 'click' | 'hover';

class Tooltip {
  private readonly _target: HTMLElement;
  private readonly _trigger: HTMLElement;
  private readonly _options: { placement: FloatingUiPlacement; offset: number; triggerType: TriggerType; };

  constructor(target: HTMLElement, trigger: HTMLElement, offset = 0, placement: FloatingUiPlacement = 'top', triggerType: TriggerType = 'hover') {
    this._target = target;
    this._trigger = trigger;
    this._options = {
      offset: offset,
      placement: placement,
      triggerType: triggerType,
    };

    const triggerEvent = this.getTriggerEvent();

    triggerEvent.showEvents.forEach(ev => {
      this._trigger.addEventListener(ev, () => {
        this.show();
      });
    });

    triggerEvent.hideEvents.forEach(ev => {
      this._trigger.addEventListener(ev, () => {
        this.hide();
      });
    });

  }

  private update(): void {
    const arrow = this._target.querySelector('.tooltip-arrow') as HTMLElement;

    floatingUi(this._trigger, this._target, {
      placement: this._options.placement,
      middleware: [floatingUiOffset(this._options.offset), floatingUiFlip(), floatingUiShift(), floatingUiArrow({element: arrow})],
    }).then(({x, y, middlewareData}) => {
      Object.assign(this._target.style, {
        left: `${x}px`,
        top: `${y}px`,
      });

      const {x: arrowX, y: arrowY} = middlewareData.arrow !== undefined ? middlewareData.arrow : {x: null, y: null};

      const staticSide = {
        top: 'bottom',
        right: 'left',
        bottom: 'top',
        left: 'right',
      }[this._options.placement.split('-')[0]];

      Object.assign(arrow.style, {
        left: arrowX != null ? `${arrowX}px` : '',
        top: arrowY != null ? `${arrowY}px` : '',
        right: '',
        bottom: '',
        [staticSide as string]: '-4px',
      });
    });
  }

  private show(): void {
    this._target.classList.remove('hidden');
    this._target.classList.add('block');

    this.update();
  }

  private hide(): void {
    this._target.classList.remove('block');
    this._target.classList.add('hidden');

  }

  private getTriggerEvent(): { hideEvents: string[]; showEvents: string[] } {
    switch (this._options.triggerType) {
      case 'hover':
        return {
          showEvents: ['mouseenter', 'focus'],
          hideEvents: ['mouseleave', 'blur'],
        };
      case 'click':
        return {
          showEvents: ['click', 'focus'],
          hideEvents: ['focusout', 'blur'],
        };
      default:
        return {
          showEvents: ['mouseenter', 'focus'],
          hideEvents: ['mouseleave', 'blur'],
        };
    }
  }
}

export default Tooltip;
